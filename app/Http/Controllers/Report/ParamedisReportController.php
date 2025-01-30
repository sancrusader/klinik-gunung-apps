<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Users\Patients;
use App\Models\Users\Paramedis;
use Barryvdh\DomPDF\Facade\pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\Clinic\PhysicalExamination;

class ParamedisReportController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Ambil ID user yang login

        // Cari paramedis berdasarkan user ID
        $paramedis = Paramedis::where('user_id', $userId)->first();

        // Pastikan paramedis ditemukan
        if (! $paramedis) {
            return back()->withErrors('Paramedis tidak ditemukan');
        }

        // Ambil data pemeriksaan fisik yang dilakukan oleh paramedis ini
        $examinations = PhysicalExamination::with('patient:id,uuid,name,date_of_birth,gender') // Hanya ambil field yang diperlukan
            ->where('paramedis_id', $paramedis->id)
            ->get();

        // Hitung total jumlah pasien yang diperiksa
        $totalPatients = $examinations->count();

        // Hitung jumlah pasien berdasarkan status kesehatan
        $healthCounts = $examinations->groupBy('health_status')->map->count();
        $sickPatientsCount = $healthCounts->get('butuh_dokter', 0);
        $needPatientsCount = $healthCounts->get('butuh_pendamping', 0);
        $healthyPatientsCount = $healthCounts->get('healthy', 0);

        // Ambil data pasien dengan validasi null
        $patients = $examinations->map(function ($examination) {
            $patient = $examination->patient;
            return [
                'id' => $patient->id ?? null,
                'uuid' => $patient->uuid ?? null,
                'name' => $patient->name ?? 'Tidak Diketahui',
                'health_status' => $examination->health_status,
                'date_of_birth' => $patient->date_of_birth ?? null,
                'gender' => $patient->gender ?? null,
            ];
        });

        // Kirim data ke frontend
        return Inertia::render('Dashboard/Paramedis/Report/Index', [
            'patients' => $patients,
            'totalPatients' => $totalPatients,
            'sickPatientsCount' => $sickPatientsCount,
            'healthyPatientsCount' => $healthyPatientsCount,
            'needPatientsCount' => $needPatientsCount,
        ]);
    }

    public function activity()
    {
        $filter = Request::input('filter', 'all');
        $startDate = null;
        $endDate = null;
    
        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::tomorrow();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }
    
        $query = PhysicalExamination::with(['patient', 'paramedis']);
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $examinations = $query->get();
    
        // Rest of your code remains the same
        $totalPatients = $examinations->count();
        $sickPatientsCount = $examinations->where('health_status', 'butuh_dokter')->count();
        $needPatientsCount = $examinations->where('health_status', 'butuh_pendamping')->count();
        $healthyPatientsCount = $examinations->where('health_status', 'healthy')->count();
        $totalParamedis = $examinations->pluck('paramedis_id')->unique()->count();
    
        $patients = $examinations->map(function ($examination) {
            return [
                'id' => $examination->patient->id,
                'name' => $examination->patient->name,
                'health_status' => $examination->health_status,
                'date_of_birth' => $examination->patient->date_of_birth,
                'gender' => $examination->patient->gender,
                'examined_by' => $examination->paramedis->name ?? 'Tidak Diketahui',
                'examined_at' => $examination->created_at->format('Y-m-d H:i:s'),
            ];
        });
    
        return Inertia::render('Dashboard/Paramedis/Activity/Index', [
            'patients' => $patients,
            'totalPatients' => $totalPatients,
            'sickPatientsCount' => $sickPatientsCount,
            'totalParamedis' => $totalParamedis,
            'needPatientsCount' => $needPatientsCount,
            'healthyPatientsCount' => $healthyPatientsCount,
            'currentFilter' => $filter,
        ]);
    }

    public function generatePDFActivity(Request $request)
    {
        $filter = Request::input('filter', 'all'); // Perbaikan pengambilan input
        $startDate = null;
        $endDate = null;
    
        switch ($filter) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::tomorrow();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }
    
        $query = PhysicalExamination::with(['patient', 'paramedis']);
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $examinations = $query->get();
    
        // Hitung statistik
        $totalPatients = $examinations->count();
        $sickPatientsCount = $examinations->where('health_status', 'butuh_dokter')->count();
        $needPatientsCount = $examinations->where('health_status', 'butuh_pendamping')->count();
        $healthyPatientsCount = $examinations->where('health_status', 'healthy')->count();
        $totalParamedis = $examinations->pluck('paramedis_id')->unique()->count();
    
        // Format data pasien
        $patients = $examinations->map(function ($examination) {
            return [
                'id' => $examination->patient->id,
                'name' => $examination->patient->name,
                'health_status' => $examination->health_status,
                'date_of_birth' => $examination->patient->date_of_birth,
                'gender' => $examination->patient->gender,
                'examined_by' => $examination->paramedis->name ?? 'Tidak Diketahui',
                'examined_at' => $examination->created_at->format('Y-m-d H:i:s'),
            ];
        });
    
        // Format tanggal untuk tampilan dan nama file
        $formattedStartDate = $startDate ? $startDate->format('Y-m-d') : 'all';
        $formattedEndDate = $endDate ? $endDate->format('Y-m-d') : 'all';
        $dateRange = $startDate && $endDate ? "{$formattedStartDate} to {$formattedEndDate}" : "All Time";
    
        // Data untuk PDF
        $data = [
            'patients' => $patients,
            'totalPatients' => $totalPatients,
            'sickPatientsCount' => $sickPatientsCount,
            'totalParamedis' => $totalParamedis,
            'healthyPatientsCount' => $healthyPatientsCount,
            'needPatientsCount' => $needPatientsCount,
            'filter' => ucfirst($filter), // Tampilkan nama filter di PDF
            'dateRange' => $dateRange, // Tambahkan rentang tanggal
        ];
    
        // Load view dengan data
        $pdf = Pdf::loadView('pdf.activity.paramedis.activity', $data);
    
        // Nama file sesuai filter dan tanggal
        return $pdf->download("activity_report_{$filter}_{$formattedStartDate}_{$formattedEndDate}.pdf");
    }

    public function generatePDFHealthCheck($id)
    {
        // Ambil data screening pasien beserta jawaban dan pemeriksaan fisik
        $screening = Patients::with(['answers.question', 'physicalExaminations.paramedis']) // Ambil data paramedis juga
            ->where('id', $id)
            ->firstOrFail(); // Pastikan pasien ditemukan

        // Ambil nama pasien untuk penamaan file PDF
        $patientName = str_replace(' ', '_', $screening->name); // Ganti spasi dengan underscore untuk nama file yang valid

        // Ambil data pemeriksaan fisik dan paramedis yang memeriksa
        $examinations = $screening->physicalExaminations;  // Mengambil semua pemeriksaan fisik yang terkait

        // Menyusun data untuk PDF
        $data = [
            'screening' => $screening,
            'examinations' => $examinations, // Kirim data pemeriksaan fisik
        ];

        // Mengonversi data menjadi PDF
        $pdf = PDF::loadView('pdf.activity.paramedis.health', $data);

        // Download PDF dengan nama yang sesuai
        return $pdf->download('health_check_'.$patientName.'.pdf');
    }

    // generate pdf  pemeriksaan yang dilakukan paramedis

    public function generatePDFself()
    {
        $userId = Auth::id();

        // Cari paramedis berdasarkan user ID
        $paramedis = Paramedis::where('user_id', $userId)->first();

        // Pastikan paramedis ditemukan
        if (! $paramedis) {
            return back()->withErrors('Paramedis tidak ditemukan');
        }

        // Ambil data pemeriksaan fisik yang dilakukan oleh paramedis ini
        $examinations = PhysicalExamination::with('patient') // Ambil data pasien terkait
            ->where('paramedis_id', $paramedis->id)
            ->get();

        // Hitung total jumlah pasien yang diperiksa
        $totalPatients = $examinations->count();

        // Hitung jumlah pasien yang sakit
        $sickPatientsCount = $examinations->where('health_status', 'butuh_dokter')->count();

        // Ambil nama-nama pasien
        $patients = $examinations->map(function ($examination) {
            return [
                'id' => $examination->patient->id,
                'name' => $examination->patient->name,
                'health_status' => $examination->health_status,
                'date_of_birth' => $examination->patient->date_of_birth,
                'gender' => $examination->patient->gender,
            ];
        });

        // Menyusun data yang akan dikirim ke view untuk PDF
        $data = [
            'patients' => $patients,
            'totalPatients' => $totalPatients,
            'sickPatientsCount' => $sickPatientsCount,
        ];

        // Load view dengan data untuk di-render menjadi PDF
        $pdf = PDF::loadView('pdf.activity.paramedis.self', $data);

        // Download PDF
        return $pdf->download('my_report.pdf');
    }
}

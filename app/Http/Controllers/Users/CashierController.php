<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Medicines\Medicine;
use App\Models\Users\Cashier;
use App\Models\Users\Patients;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Cashier/Index');
    }

    public function profile()
    {
        return Inertia::render('Profile/Cashier');
    }
    /**
     * Display screening offline
     */
    public function showScreeningOffline()
    {
        // Muat screening yang statusnya pending dan memiliki jawaban
        $screenings = Patients::with(['answers.question'])
            ->where('payment_status', 'pending')
            ->whereHas('answers', function ($query) {
                $query->whereNotNull('answer_text');
            })
            ->get();

        // Muat semua obat
        $medicines = Medicine::with('pricing')->get();

        return Inertia::render('Dashboard/Cashier/Screenings/ScreeningOffline', [
            'screenings' => $screenings,
            'medicines' => $medicines,
        ]);
    }


    public function historyPaymentsOffline()
    {
        // Ambil data pasien dengan pembayaran dan screening yang relevan
        $patients = Patients::with([
            'payments',                // Relasi ke payments
            'answers.question',        // Relasi ke jawaban dan pertanyaan screening
        ])
        ->whereHas('payments', function ($query) {
            $query->where('payment_status', true); // Hanya yang sudah selesai pembayarannya
        })
        ->get();
    
        // Muat semua data obat dengan pricing
        $medicines = Medicine::with('pricing')->get();
    
        return Inertia::render('Dashboard/Cashier/Payments/HistoryPaymentsScreeningOffline', [
            'patients' => $patients,
            'medicines' => $medicines,
        ]);
    }
    
}
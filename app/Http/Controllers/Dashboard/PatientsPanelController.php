<?php

namespace App\Http\Controllers\Dashboard;

use Inertia\Inertia;
use App\Models\Users\Patients;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\VisitRepository;
use Illuminate\Support\Facades\Auth;

class PatientsPanelController extends Controller
{


    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();  // Menyimpan user di properti kelas
    }

    /**
     * Display the patient's dashboard.
     * 
     * This method verifies the user's patient profile, logs daily visits,
     * and calculates visit counts in the last 3 months.
     *
     */
    public function index(VisitRepository $visitRepository)
    {
        // Get the authenticated user's details

        // Check if the user has a patient profile
        $patient = Patients::where('user_id', $this->user->id)->first();
        
        if (!$patient) {
            return redirect()->route('information.index');
        }
        
        // Render the patient's dashboard view with the visit count
        return Inertia::render('Dashboard/Patients/Index', [
            'visitCount' => $visitRepository->visitCount($this->user)

        ]);
    }

    public function profile()
    {
        $patient = Patients::where('user_id', $this->user->id)->first();

        if (!$patient) {
            return redirect()->route('information.index')
                ->with('warning', 'Please complete your patient profile before accessing appointments.');
        }
        return Inertia::render('Profile/Patients');
    }

}

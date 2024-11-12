<?php

namespace App\Http\Controllers\Users;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Users\Patients;
use App\Models\Activity\UserVisit;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        if (Auth::check()) {
            $todayVisit = UserVisit::where('user_id', $userId)
                ->whereDate('visit_date', now()->toDateString())
                ->first();

            if (!$todayVisit) {
                UserVisit::create([
                    'user_id' => $userId,
                    'visit_date' => now(),
                ]);
            }
        }
        $visitCount = UserVisit::where('user_id', $userId)
            ->where('visit_date', '>=', now()->subMonths(3))
            ->count();

        return Inertia::render('Dashboard/Patients/Index', [
            "visitCount" => $visitCount,
        ]);
    }

    public function profile()
    {
        return Inertia::render('Profile/Patients');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patients $patients)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patients $patients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patients $patients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patients $patients)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Auth\SocialLogin;
use App\Models\User;
use App\Repositories\VisitRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPanelController extends Controller
{
    public function index(User $user, VisitRepository $visitRepository)
    {
        return Inertia::render('Dashboard/Admin/Index', [
            'visitCount' => $visitCount = $visitRepository->visitCount($user),
        ]);
    }

    public function AuthSetting()
    {
        // Ambil data social login dari database (entri pertama)
        $social = SocialLogin::firstOrCreate([], [
            'google' => false,
        ]);

        return Inertia::render('Dashboard/Admin/AuthSettings/Social', [
            'initialData' => [
                'google' => $social->google,
            ],
        ]);
    }

    public function updateAuth(Request $request)
    {
        $socialLogin = SocialLogin::first(); // Ambil pengaturan pertama
        if ($socialLogin) {
            $socialLogin->update($request->only(['google']));
        }

        return redirect()->back()->with('message', 'Social login settings updated successfully!');
    }
}

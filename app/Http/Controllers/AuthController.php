<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:academic_administrator,researcher,research_staff',
        ]);

        if (!str_ends_with(strtolower($request->email), '@usep.edu.ph')) {
            return redirect()->back()->withErrors(['email' => 'Only @usep.edu.ph email addresses are allowed.']);
        }

        $credentials = $request->only('email', 'password');
        $role = $request->input('role');

        switch ($role) {
            case 'academic_administrator':
                if (Auth::guard('academic_administrator')->attempt($credentials)) {
                    return redirect()->route('academic_administrator.dashboard');
                }
                break;

            case 'researcher':
                if (Auth::guard('researcher')->attempt($credentials)) {
                    return $this->redirectToDashboard();
                }
                break;

            case 'research_staff':
                if (Auth::guard('research_staff')->attempt($credentials)) {
                    return redirect()->route('research_staff.dashboard');
                }
                break;

            default:
                return redirect()->back()->withErrors(['role' => 'Invalid role selected.']);
        }

        return redirect()->back()->withErrors([
            'message' => 'Invalid credentials or role. Ensure your email, password, and role are correct.',
        ]);
    }

    protected function redirectToDashboard()
    {
        $researcher = Auth::guard('researcher')->user();
        if (!$researcher) {
            return redirect()->route('login')->withErrors(['message' => 'Authentication failed. Please log in again.']);
        }
        return redirect()->route('researcher.dashboard', ['id' => $researcher->id]);
    }

    public function logout(Request $request)
    {
        $guard = null;
        foreach (['academic_administrator', 'researcher', 'research_staff'] as $role) {
            if (Auth::guard($role)->check()) {
                $guard = $role;
                break;
            }
        }

        if ($guard) {
            Auth::guard($guard)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', ucfirst(str_replace('_', ' ', $guard)) . ' logged out successfully.');
        }

        return redirect()->route('login')->withErrors(['message' => 'No authenticated user found.']);
    }
}

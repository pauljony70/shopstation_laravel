<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Admin;

class AuthController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Check if the user is already logged in
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard'); // Adjust the route name accordingly
        }

        return view('admin.auth.login');
    }

    /**
     * Handle the login process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt to log in the user using the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication passed

            // Check if there is a previous intended URL
            $intendedUrl = session()->get('url.intended', route('admin.dashboard'));

            // Redirect to the intended URL or to admin
            return redirect()->to($intendedUrl);
        }

        // Authentication failed
        return redirect()->route('admin.login.form')->with('erro_login', 'Invalid login credentials');
    }


    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        return view('admin.dashboard'); // Adjust the view name accordingly
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate email and password input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the admin by email
        $admin = Admin::where('email', $request->email)->first();

        // Check if admin exists and password is correct
        if ($admin && Hash::check($request->password, $admin->password)) {
            // if ($request->password == $admin->password) {
            // Log in the admin using Laravel authentication
            Auth::guard('admin')->login($admin);

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Redirect to dashboard
            return redirect()->route('dashboard.index');
        }

        // If login fails, return back with an error
        return back()->withErrors(['email' => 'Invalid credentials']);
    }



    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('auth.login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IpMapping;

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

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->with('user')
            ->latest()
            ->get();

        $recentPosts = \App\Models\ForumPost::with('user')
            ->where('created_at', '>=', now()->subDay())
            ->latest()
            ->take(5)
            ->get();

        // Check for IP mapping
        $clientIp = $request->ip();
        $ipMapping = IpMapping::where('ip_address', $clientIp)
            ->where('is_active', true)
            ->first();

        return view('auth.login', compact('announcements', 'recentPosts', 'ipMapping'));
    }

    /**
     * Log the user in based on their IP mapping.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function fastLogin(Request $request)
    {
        $clientIp = $request->ip();
        $ipMapping = IpMapping::where('ip_address', $clientIp)
            ->where('is_active', true)
            ->first();

        if ($ipMapping) {
            $user = $ipMapping->getOrCreateUser();

            // Clear manual logout flag since user is explicitly choosing to log in
            $request->session()->forget('manual_logout');

            Auth::login($user, true); // Remember the user
            $ipMapping->updateLastUsed();

            return redirect()->intended($this->redirectPath())->with('status', 'Welcome back! You have been logged in automatically via IP.');
        }

        return redirect()->route('login')->with('error', 'IP mapping not found or inactive.');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        // Set a session flag to prevent immediate automatic re-login
        $request->session()->put('manual_logout', true);

        return redirect()->route('login');
    }
}

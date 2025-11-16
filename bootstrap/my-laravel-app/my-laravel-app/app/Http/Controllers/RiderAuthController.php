<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // make sure this exists

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class RiderAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('rider.login');
    }

    

    public function login(Request $request)
{
    // 1️⃣ Validate input
    $request->validate([
    'email' => 'required|email',
    'password' => 'required|min:6',
], [
    'email.required' => 'Please enter your email address.',
    'password.required' => 'Please enter your password.',
]);


    // 2️⃣ Try to find rider by email
    $rider = Rider::where('email', $request->email)->first();

    // 3️⃣ Check if rider exists and password matches
    if ($rider && Hash::check($request->password, $rider->password)) {

        // 4️⃣ Log in the rider by setting session
        session()->put('rider_id', $rider->id);
        session()->put('rider_name', $rider->name); // optional, for display in dashboard

        // 5️⃣ Redirect to rider dashboard using route name
        return redirect()->route('rider.dashboard');
    }

    // 6️⃣ If login fails, redirect back with error
    return back()->withErrors([
        'login_error' => 'Invalid email or password, or you are not registered as a Rider.',
    ])->withInput(); // keeps old email in form
}


    public function dashboard()
{
    // Redirect to login if not authenticated
    if (!session()->has('rider_id')) {
        return redirect()->route('rider.login');
    }

    $rider = Rider::find(session('rider_id'));

    // Fetch transactions assigned to this rider
    $transactions = Transaction::where('rider_id', $rider->id)->get();

    // Example delivery history
    $history = collect([
        (object) ['id' => 1, 'date' => '2025-10-17', 'customer' => 'Juan Dela Cruz', 'status' => 'Delivered'],
        (object) ['id' => 2, 'date' => '2025-10-16', 'customer' => 'Maria Santos', 'status' => 'Pending'],
    ]);

    return view('rider.dashboard', compact('rider','transactions', 'history'));
}



    public function history()
{
    if (!session()->has('rider_id')) {
        return redirect()->route('rider.login');
    }

    $rider = Rider::find(session('rider_id'));

    $history = collect([
        (object) ['id' => 1, 'date' => '2025-10-17', 'customer' => 'Juan Dela Cruz', 'status' => 'Delivered'],
        (object) ['id' => 2, 'date' => '2025-10-16', 'customer' => 'Maria Santos', 'status' => 'Pending'],
    ]);

    return view('rider.dashboard', compact('rider', 'history'));
}


    public function logout()
{
    session()->flush(); // wipe all session data
    return redirect()->route('rider.login');
}

    
}




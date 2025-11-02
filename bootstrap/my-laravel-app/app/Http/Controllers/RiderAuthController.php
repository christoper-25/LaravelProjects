<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $rider = Rider::where('email', $request->email)->first();

        if ($rider && Hash::check($request->password, $rider->password)) {
            session(['rider_id' => $rider->id]);
            return redirect('/rider/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        if (!session('rider_id')) {
            return redirect('/rider/login');
        }

        $rider = Rider::find(session('rider_id'));
        return view('rider.dashboard', compact('rider'));
    }

    public function logout()
    {
        session()->forget('rider_id');
        return redirect('/rider/login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Models\Transactions;
use Illuminate\Http\Request;
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

        return back()->withErrors([
            'email' => 'You are not registered as Delivery Rider! Please contact the Admin.'
        ]);
    }

    public function dashboard()
{
    

}
public function history()

    {
        if (!session('rider_id')) {
        return redirect('/rider/login');
    }

    $rider = Rider::find(session('rider_id'));

    // ✅ Temporary delivery history
    $history = collect([
        (object) ['id' => 1, 'date' => '2025-10-17', 'customer' => 'Juan Dela Cruz', 'status' => 'Delivered'],
        (object) ['id' => 2, 'date' => '2025-10-16', 'customer' => 'Maria Santos', 'status' => 'Pending'],
    ]);
            return view('rider.dashboard', compact('rider', 'history'));
    }




    // ✅ Make sure BOTH rider and history are passed to the view




    public function logout()
    {
        session()->forget('rider_id');
        return redirect('/rider/login');
    }
}

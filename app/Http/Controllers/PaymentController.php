<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
        ]);

        $payment = Payment::create($request->all());

        // Update the user's outstanding fees
        $user = User::findOrFail($request->user_id);
        $user->outstanding_fees -= $request->amount;
        $user->save();

        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->all());

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(null, 204);
    }

    public function showByUser($userId)
    {
        $payments = Payment::where('user_id', $userId)->get();
        return response()->json($payments);
    }
}

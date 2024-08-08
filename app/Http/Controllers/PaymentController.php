<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }


    public function makePayment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fees_id' => 'required|exists:fees,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
        ]);

        $user = User::find($request->user_id);

        $department = Department::where('name', $user->department)->first();

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $fees = Fee::where('department_id', $department->id)->first();

        if (!$fees) {
            return response()->json(['message' => 'Fees for the department not found'], 404);
        }

        $outstandingFees = $user->outstanding_fees;
        $newOutstandingFees = $outstandingFees - $request->amount;
        $user->update(['outstanding_fees' => $newOutstandingFees]);

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'fees_id' => $request->user_id,
            'amount' => $request->amount,
            'outstanding_fees' => $newOutstandingFees,
        ]);

        $user->outstanding_fees = $newOutstandingFees;
        $user->save();

        return response()->json(['message' => 'Payment recorded successfully!', 'payment' => $payment]);
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


    public function viewPayments()
    {
        $user = Auth::user();
        $payments = Payment::where('user_id', $user->id)->get();

        return response()->json(['payments' => $payments, 'outstanding_fees' => $user->outstanding_fees]);
    }
}

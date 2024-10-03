<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeesController extends Controller
{

    public function index()
    {
        $fees = Fee::all();
        return response()->json($fees);
    }


    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required|string|max:255|unique:fees',
            'amount' => 'required|numeric',
        ]);

        $fee = Fee::create($request->all());
        return response()->json($fee, 201);
    }


    public function showMyFees($id)
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return response()->json(['message' => 'Not authorized.'], 403);
        }

        $fee = Fee::where('department', $user->department)->first();
        if (!$fee) {
            return response()->json(['message' => 'No fee found for your department.'], 404);
        }

        return response()->json([
            'department' => $user->department,
            'outstanding_fees' => $user->outstanding_fees,
            'total_fees' => $fee->amount,
        ]);
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'department' => 'sometimes|string|max:255|unique:fees,department,' . $id,
            'amount' => 'required|numeric',
        ]);

        $fee = Fee::findOrFail($id);
        $fee->update($request->all());
        return response()->json($fee);
    }


    public function destroy(string $id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();
        return response()->json(null, 204);
    }

    public function showOutstandingFees($id)
    {

        $user = Auth::user();
        if ($user->role !== 'student') {
            return response()->json(['message' => 'Not authorized.'], 403);
        }

        return response()->json([
            'outstanding_fees' => $user->outstanding_fees,
        ]);
    }
}

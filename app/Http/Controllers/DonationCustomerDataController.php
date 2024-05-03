<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Donation_CustomerData;
use Illuminate\Http\Request;

class DonationCustomerDataController extends Controller
{
    public function index()
    {
        $donationCustomerData = Donation_CustomerData::all();
        return response()->json($donationCustomerData, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:donations,Service_id',
            'Amount' => 'required|numeric',
            'extra_fields' => 'nullable|array',
        ]);

        $donationCustomerData = Donation_CustomerData::create($request->all());
        return response()->json($donationCustomerData, 201);
    }

    public function show($id)
    {
    $donationCustomerData = DB::connection('mongodb')->collection('Donation_CustomerData')->where('_id',$id)->first();

    // Check if data is found
    if (!$donationCustomerData) {
        return response()->json(['message' => 'Donation customer data not found'], 404);
    }

    return response()->json($donationCustomerData, 200);
    }


    public function destroy($id)
    {
    DB::connection('mongodb')->collection('Donation_CustomerData')->where('_id', $id)->delete();
    return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\MobileInternetBill_CustomerData;
use Illuminate\Http\Request;

class MobileInternetBillCustomerDataController extends Controller
{
    public function index()
    {
        $BillCustomerData = MobileInternetBill_CustomerData::all();
        return response()->json($BillCustomerData, 200);
        
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required',
            'Phone_Number' => 'required',
            'Amount' => 'required',
            'extra_fields' => 'nullable|array',
        ]);


        $BillCustomerData = MobileInternetBill_CustomerData::create($request->all());
        return response()->json($BillCustomerData, 201);
    }


    public function show($id)
    {
    $BillCustomerData = DB::connection('mongodb')->collection('MobileInternetBill_CustomerData')->where('_id',$id)->first();

    // Check if data is found
    if (!$BillCustomerData) {
        return response()->json(['message' => ' customer data not found'], 404);
    }

    return response()->json($BillCustomerData, 200);
    }


    public function destroy($id)
    {
    DB::connection('mongodb')->collection('MobileInternetBill_CustomerData')->where('_id', $id)->delete();
    return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\UtilityBill_CustomerData;
use Illuminate\Http\Request;

class UtilityBillCustomerDataController  extends Controller
{
    public function index()
    {
        $BillCustomerData = UtilityBill_CustomerData::all();
        return response()->json($BillCustomerData, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:donations,Service_id',
            'Payment_Code'=> 'required|numeric',
            'extra_fields' => 'nullable|array',
        ]);


        $BillCustomerData = UtilityBill_CustomerData::create($request->all());
        return response()->json($BillCustomerData, 201);
    }

    public function show($id)
    {
    $BillCustomerData = DB::connection('mongodb')->collection('UtilityBill_CustomerData')->where('_id',$id)->first();

    // Check if data is found
    if (!$BillCustomerData) {
        return response()->json(['message' => 'UtilityBill customer data not found'], 404);
    }

    return response()->json($BillCustomerData, 200);
    }

    public function destroy($id)
    {
    DB::connection('mongodb')->collection('UtilityBill_CustomerData')->where('_id', $id)->delete();
    return response()->json(null, 204);
    }
    
}

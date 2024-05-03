<?php

namespace App\Http\Controllers;

use App\Models\MobileInternetBill;
use Illuminate\Http\Request;

class MobileInternetBillController extends Controller
{
    public function index()
    {
        $bills = MobileInternetBill::all();
        return response()->json($bills, 200);
    }

    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ServiceProviderID' => 'required|exists:service_providers,ServiceProviderID',
            'Support_Contact_Number' => 'required|string|regex:/^\d{12}$/',
            'Mobile_code' => 'required|string|size:3',
            'extra_fields' => 'sometimes|array',
        ]);

    
        $bill =  MobileInternetBill::create($request->all());
        return response()->json($bill, 201);
    }

 
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Support_Contact_Number' => 'required|string|regex:/^\d{12}$/',
            'Mobile_code' => 'required',
            'extra_fields' => 'nullable|array',
        ]);

        $bill = MobileInternetBill::findOrFail($id);

        /// Get the request data with non-null values
       $requestData = array_filter($request->all());

        // Update only the existing fields in the ticket booking
       $bill->fill($requestData)->save();

       return response()->json($bill, 200);
    }



    public function show($id)
    {
        $bill = MobileInternetBill::findOrFail($id);
        return response()->json($bill, 200);
    }


    public function destroy($id)
    {

        $bill = MobileInternetBill::findOrFail($id);
        $bill->delete();
        return response()->json(null, 204);
    }

    
}
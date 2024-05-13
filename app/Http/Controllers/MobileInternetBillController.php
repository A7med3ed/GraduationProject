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
            
            'Support_Contact_Number' => 'required|string|regex:/^\d{3}$/',
            'Mobile_code' => 'required|string|size:3',
            'extra_fields' => 'nullable|array',
        ]);

        $Service_id = MobileInternetBill::max('Service_id') + 1;
        $bill = MobileInternetBill::create([
            'Service_id'=>$Service_id,
            'ServiceProviderID'=>auth()->user()->ServiceProviderID,
            'Support_Contact_Number' => $request->input('Support_Contact_Number'),
            'Mobile_code' => $request->input('Mobile_code'),
            'icon' => $request->input('icon'),
            'Type' => $request->input('Type'),
            'extra_fields' => $request->input('extra_fields'),
        ]);

        return response()->json($bill, 200);
    }

 
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Support_Contact_Number' => 'required|string|regex:/^\d{12}$/',
            'Mobile_code' => 'required',
            'extra_fields' => 'nullable|array',
        ]);

        $bill = MobileInternetBill::findOrFail($id);

        // Get the request data with non-null values
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
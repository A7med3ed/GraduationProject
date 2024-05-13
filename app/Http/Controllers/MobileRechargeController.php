<?php

namespace App\Http\Controllers;

use App\Models\MobileRecharge;
use Illuminate\Http\Request;

class MobileRechargeController extends Controller
{

    public function index()
    {
        $recharges = MobileRecharge::all();
        return response()->json($recharges, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Support_Contact_Number' => 'required|string|regex:/^\d{3}$/',
            'Min_Recharge' => 'required',
            'Max_Recharge' => 'required',
            'Mobile_code' => 'required|string|regex:/^\d{3}$/',
            'extra_fields' => 'nullable|array',
        ]);
        $Service_id = MobileRecharge::max('Service_id') + 1;
        $recharge = MobileRecharge::create([
            'Service_id'=>$Service_id,
            'ServiceProviderID'=>auth()->user()->ServiceProviderID,
            'Support_Contact_Number' => $request->input('Support_Contact_Number'),
            'Mobile_code' => $request->input('Mobile_code'),
            'Min_Recharge' => $request->input('Min_Recharge'),
            'Max_Recharge' => $request->input('Max_Recharge'),
            'icon' => $request->input('icon'),
            'extra_fields' => $request->input('extra_fields'),
        ]);
        

        return response()->json($recharge, 200);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'Support_Contact_Number' => 'required|string|regex:/^\d{3}$/',
            'Min_Recharge' => 'required',
            'Max_Recharge' => 'required',
            'Mobile_code' => 'required',
            'extra_fields' => 'nullable|array',
        ]);

        $recharge = MobileRecharge::findOrFail(auth()->user()->ServiceProviderID);

        // Get the request data with non-null values
        $requestData = array_filter($validatedData);

        // Update only the existing fields in the ticket booking
        $recharge->fill($requestData)->save();

        return response()->json($recharge, 200);
    }

    public function show($id)
    {
        $recharges = MobileRecharge::findOrFail($id);
        return response()->json($recharges, 200);
    }


    public function destroy($id)
    {
        $recharges = MobileRecharge::findOrFail($id);
        $recharges->delete();
        return response()->json(null, 204);
    }
}
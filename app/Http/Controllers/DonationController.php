<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::all();
        
        foreach ($donations as $donation) {
            $serviceProviderID = $donation->ServiceProviderID;
            $organizationName = ServiceProvider::find($serviceProviderID)->organizationName;
            $donation->setAttribute('organizationName', $organizationName);
        }
    
        return response()->json($donations, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Support_Contact_Number' => 'required|string|regex:/^\d{3}$/',
            'Donation_Purpose' => 'required|string',
            'Address' => 'required|string',
            'extra_fields' => 'nullable|array',
        ]);
        $Service_id = Donation::max('Service_id') + 1;
        $donation = Donation::create([
            'Service_id'=>$Service_id,
            'ServiceProviderID'=>auth()->user()->ServiceProviderID,
            'Support_Contact_Number' => $request->input('Support_Contact_Number'),
            'Donation_Purpose' => $request->input('Donation_Purpose'),
            'icon' => $request->input('icon'),
            'Address' => $request->input('Address'),
            'extra_fields' => $request->input('extra_fields'),
        ]);

        return response()->json($donation, 200);
    }

    public function show($id)
    {
        $donation = Donation::findOrFail($id);
        return response()->json($donation, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Support_Number' => 'sometimes|string|regex:/^\d{12}$/',
            'Donation_Purpose' => 'sometimes|string',
            'Address' => 'sometimes|string',
            'extra_fields' => 'sometimes|array',
        ]);

        $donation = Donation::findOrFail($id);

         /// Get the request data with non-null values
        $requestData = array_filter($request->all());

         // Update only the existing fields in the ticket booking
        $donation->fill($requestData)->save();

        return response()->json($donation, 200);
    }

    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->delete();
        return response()->json(null, 204);
    }
}
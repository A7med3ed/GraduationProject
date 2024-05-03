<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::all();
        return response()->json($donations, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ServiceProviderID' => 'required|exists:service_providers,ServiceProviderID',
            'Support_Number' => 'required|string|regex:/^\d{12}$/',
            'Donation_Purpose' => 'sometimes|string',
            'Address' => 'sometimes|string',
            'extra_fields' => 'sometimes|array',
        ]);

        $donation = Donation::create($request->all());
        return response()->json($donation, 201);
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

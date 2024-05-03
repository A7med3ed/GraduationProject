<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceProvider;
use App\Models\ProviderWallet;

class ServiceProviderController extends Controller
{
    // Retrieve a single service provider by Email and Password
    public function show(Request $request)
    {
        $request->validate([
        'RepEmail' => 'required|email',
        'Password' => 'required|string|min:6'
        ]);

        $serviceProvider = ServiceProvider::where('RepEmail', $request->input('RepEmail'))->first();

        if ($serviceProvider && Hash::check($request->input('Password'), $serviceProvider->Password)) {

            $token =$serviceProvider->CreateToken('auth_token')->plainTextToken;

            // Return the user data
            return response()->json(['ServiceProvider'=>$serviceProvider,'Token'=>$token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    // Store a new service provider
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'organizationName' => 'required|string|unique:service_providers',
            'RepName' => 'required|string',
            'RepEmail' => 'required|email|unique:service_providers',
            'Password' => 'required|string|min:6|regex:/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{6,}$/',
            'RepPhoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
        ]);

        // Generate a random numeric for ID
        $serviceProviderID = ServiceProvider::max('ServiceProviderID') + 1;

        // Create a new service provider
        $serviceProvider = ServiceProvider::create([
            'ServiceProviderID' => $serviceProviderID,
            'organizationName' => $request->input('organizationName'),
            'RepName' => $request->input('RepName'),
            'RepEmail' => $request->input('RepEmail'),
            'Password' => Hash::make($request->input('Password')),
            'RepPhoneNumber' => $request->input('RepPhoneNumber'),
        ]);

        // Create a ProviderWallet for the new service provider with 0 balance
        $providerWallet = ProviderWallet::create([
            'ServiceProviderID' => $serviceProviderID,
            'Balance' => 0,
        ]);

        // Call the show method to get the token
        $tokenResponse = $this->show($request);

        // Extract the token from the response
        $token = $tokenResponse->getData()->Token;

        // Return the created user along with the token
        return response()->json(['serviceProvider' => $serviceProvider, 'ProviderWallet' => $providerWallet, 'Token' => $token], 201);
    }


    // Update an existing service provider
    public function update(Request $request, $ServiceProviderID)
    {
        $request->validate([
            'RepName' => 'sometimes|required|string',
            'RepEmail' => 'sometimes|required|email|unique:service_providers,RepEmail,' . $ServiceProviderID,
            'Password' => 'required|string|min:6|regex:/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{6,}$/'
        ]);

        $serviceProvider = ServiceProvider::findOrFail($ServiceProviderID);

         // Update user fields if they are present in the request and not null
        if ($request->filled('RepName')) {
            $serviceProvider->RepName= $request->input('RepName');
        }
    
        if ($request->filled('RepEmail')) {
            $serviceProvider->RepEmail = $request->input('RepEmail');
        }
    
        if ($request->filled('Password')) {
            $serviceProvider->Password = Hash::make($request->input('Password'));
        }
    
        // Save the updated Provider
        $serviceProvider->save();

        return response()->json($serviceProvider, 200);
    }

    // Delete a service provider
    public function destroy($ServiceProviderID)
    {
        $serviceProvider = ServiceProvider::findOrFail($ServiceProviderID);
        $serviceProvider->delete();
        return response()->json(null, 204);
    }
}

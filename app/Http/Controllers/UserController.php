<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Wallet;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve all users
        $users = User::all();
        return response()->json($users);
    }

    public function show(Request $request)
    {
        // Validate input
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required|string|min:6',
        ]);
    
        // Find the user by email
        $user = User::where('Email', $request->input('Email'))->first();
    
        // Check if user exists and if the provided password matches the hashed password
        if ($user && Hash::check($request->input('Password'), $user->Password)) {
        
            $token =$user->CreateToken('auth_token')->plainTextToken;
            // Return the user data
            return response()->json(['User'=>$user,'Token'=>$token]);
        } else {
            // If user doesn't exist or password doesn't match, return an error response
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:users',
            'National_ID' => 'required|string|regex:/^[0-9]+$/|unique:users',
            'Password' => 'required|string|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/',
            'Phone_Number' => 'required|string|regex:/^[0-9]+$/|unique:users',
            'Date_of_Birth' => 'required|date'
        ]);
    
        // Generate a random numeric user_id
        $user_id = User::max('user_id') + 1;
    
        // Create a new user
        $user = User::create([
            'user_id' => $user_id,
            'Name' => $request->input('Name'),
            'Email' => $request->input('Email'),
            'National_ID' => $request->input('National_ID'),
            'Password' => Hash::make($request->input('Password')), // Hash the password 
            'Phone_Number' => $request->input('Phone_Number'),
            'Date_of_Birth' => $request->input('Date_of_Birth'),
        ]);

        // Create a wallet for the user
        $wallet = Wallet::create([
            'user_id' => $user_id,
            'Balance' => 0, // initial balance is 0
        ]);
    

    // Call the show method to get the token
    $tokenResponse = $this->show($request);

    // Extract the token from the response
    $token = $tokenResponse->getData()->Token;

    // Return the created user along with the token
    return response()->json(['user' => $user, 'Token' => $token], 201);
    }

    public function update(Request $request, $user_id)
    {
        

        // Validate input
        $request->validate([
            'Name' => 'sometimes|required|string|max:255',
            'Email' => 'sometimes|required|email',
            'Password' => 'sometimes|required|string|min:6',
        ]);
    
        // Find the user
        $user = User::where('user_id', $user_id)->firstOrFail();
    
        // Update user fields if they are present in the request and not null
        if ($request->filled('Name')) {
            $user->Name = $request->input('Name');
        }
    
        if ($request->filled('Email')) {
            $user->Email = $request->input('Email');
        }
    
        if ($request->filled('Password')) {
            $user->Password = Hash::make($request->input('Password'));
        }

        // Save the updated user
        $user->save();

        return response()->json($user, 200);

    }
    
    
    public function destroy($user_id)
    {
        // Find the user
        $user = User::where('user_id',$user_id)->firstOrFail();

        // Delete the user
        $user->delete();

        return response()->json(null, 204);
    }
}

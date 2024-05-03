<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Mail\emailMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\BankTransaction;
use App\Models\CardTransaction;
use App\Models\UserTransaction;
use App\Models\ProviderTransaction;
use App\Models\TicketBooking;
use App\Models\Donation;
use App\Models\MobileInternetBill;
use App\Models\UtilityBill;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function collect(Request $request)
    {
        $request->validate([
            'Phone_Number' => 'required|exists:users,phone_number',
            'amount_requested' => 'required|numeric',
        ]);

        // Get the user by phone number
        $user = User::where('phone_number', $request->Phone_Number)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate a random verification code
        $verificationCode = rand(100000, 999999);

        // Save the verification code and expiration time in the session
        $expirationTime = now()->addHour(); // Verification code will expire in 1 hour
        Session::put('verification_code', $verificationCode);
        Session::put('verification_code_expires_at', $expirationTime);

        // Send the verification code to the user via email
        Mail::to($user->email)->send(new emailMailable($verificationCode, $request->amount_requested));

        return response()->json(['message' => 'Verification code sent successfully'], 200);
    }


    public function sendVerificationEmail(Request $request)
    {
        $request->validate([
            'receiver_email' => 'required|email',
        ]);

        // Generate a random verification code
        $verificationCode = rand(100000, 999999);

        // Save the verification code and expiration time in the session
        $expirationTime = now()->addHour(); // Verification code will expire in 1 hour
        Session::put('verification_code', $verificationCode);
        Session::put('verification_code_expires_at', $expirationTime);

        // Send the verification code to the user via email
        Mail::to($request->receiver_email)->send(new emailMailable($verificationCode));

        return response()->json(['message' => 'Verification code sent successfully'], 200);
    }



    public function verifyCode(Request $request)
    {
    $request->validate([
        'verification_code' => 'required|numeric',
    ]);

    // Get the verification code from the session
    $storedCode = Session::get('verification_code');

    // Get the provided verification code from the request
    $providedCode = $request->input('verification_code');

    // Check if the provided code matches the stored code
    if ($providedCode == $storedCode) {
        // Code is correct
        // Clear the verification code from the session
        Session::forget('verification_code');

        return response()->json(['message' => 'Verification successful'], 200);
    } else {
        // Code is incorrect
        return response()->json(['message' => 'Invalid verification code'], 400);
    }

    }


    public function getUserHistory(Request $request)
    {
        // Retrieve sender_id if provided
        $senderId = $request->input('sender_id');

        // Initialize an empty array to store all transactions
        $allTransactions = [];

        // Retrieve transactions from each type and add them to the array
        $allTransactions = array_merge(
            $allTransactions,
            CardTransaction::where('sender_id', $senderId)->get()->toArray()
        );

        $allTransactions = array_merge(
            $allTransactions,
            BankTransaction::where('sender_id', $senderId)->get()->toArray()
        );

        $allTransactions = array_merge(
            $allTransactions,
            ProviderTransaction::where('sender_id', $senderId)->get()->toArray()
        );

        $allTransactions = array_merge(
            $allTransactions,
            UserTransaction::where('sender_id', $senderId)->get()->toArray()
        );

        // Return JSON response containing all transactions
        return response()->json($allTransactions);
    }
    

 

public function showServices(Request $request)
{
    $ServiceProviderID = $request->input('ServiceProviderID');

    // Retrieve all services owned by the specified ServiceProviderID
    $ticketBookings = TicketBooking::where('ServiceProviderID', $ServiceProviderID)->get();
    $donations = Donation::where('ServiceProviderID', $ServiceProviderID)->get();
    $mobileInternetBills = MobileInternetBill::where('ServiceProviderID', $ServiceProviderID)->get();
    $utilityBills = UtilityBill::where('ServiceProviderID', $ServiceProviderID)->get();

    // Combine all service results into a single array
    $services = [
        'ticketBookings' => $ticketBookings,
        'donations' => $donations,
        'mobileInternetBills' => $mobileInternetBills,
        'utilityBills' => $utilityBills,
    ];

    // Check if any services are found
    $isEmpty = true;
    foreach ($services as $service) {
        if (!$service->isEmpty()) {
            $isEmpty = false;
            break;
        }
    }

    if ($isEmpty) {
        return response()->json(['message' => 'No services found for the specified ServiceProviderID'], 404);
    }

    return response()->json($services, 200);
}


}

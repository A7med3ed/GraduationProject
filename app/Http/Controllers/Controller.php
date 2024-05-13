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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomerNotification;


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

        Notification::send(($user), new CustomerNotification("Send Money", null, $request->input('amount_requested')));

        return response()->json(['message' => 'Verification code sent successfully'], 200);
    }

    public function multiCollect(Request $request)
    {
    $request->validate([
        'Phone_Numbers' => 'required|array',
        'amount_requested' => 'required|numeric',
    ]);

    $phoneNumbers = $request->Phone_Numbers;
    $usersNotFound = [];
    $usersNotified = [];

    foreach ($phoneNumbers as $phoneNumber) {
        // Get the user by phone number
        $user = User::where('phone_number', $phoneNumber)->first();

        if (!$user) {
            $usersNotFound[] = $phoneNumber;
            continue;
        }

        Notification::send($user, new CustomerNotification("Send Money", null, $request->input('amount_requested')));
        $usersNotified[] = $user->phone_number;
    }

    $responseMessage="all users requsted";
    if (!empty($usersNotFound)) {
        $responseMessage = '. Users not found for phone numbers: ' . implode(', ', $usersNotFound);
    }

    return response()->json(['message' => $responseMessage], 200);

    }


    
    public function sendVerificationEmail()
    {
    // Generate a random verification code with 4 digits
    $verificationCode = rand(1000,9999);

    // Get the authenticated user
    $user = auth()->user();

    // Store the verification code in the cache for a certain duration
    Cache::put('user:verification_code:'.$user->id, $verificationCode, now()->addMinutes(10));

   // Send the verification code to the user via email
    Mail::to('sheryshawky2001@gmail.com')->send(new emailMailable($verificationCode));

    return response()->json(['message' => 'Verification code sent successfully'], 200);

    } 


    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric',
        ]);
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Get the provided verification code from the request
        $providedCode = $request->input('verification_code');
        
        // Retrieve the verification code from the cache
        $storedCode = Cache::get('user:verification_code:'.$user->id);
        
        // Check if the provided code matches the stored code
        if ($providedCode == $storedCode) {

            Cache::forget('user:verification_code:'.$user->id);

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
        return response()->json($allTransactions,200);
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


public function getNotifications()
{
    // Get the authenticated user
    $user = auth()->user();

    // Fetch unread notifications for the user
    $unreadNotifications = $user->unreadNotifications;

    // Get the count of unread notifications
    $unreadCount = $unreadNotifications->count();

    // Prepare the data to be returned
    $data = [
        'unread_notifications' => $unreadNotifications,
        'unread_count' => $unreadCount,
    ];

    // Mark all unread notifications as read
    $user->unreadNotifications->markAsRead();

    // Return JSON response with all notifications and their counts
    return response()->json($data,200);
}





}

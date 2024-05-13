<?php

namespace App\Http\Controllers;

use App\Models\UserTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use App\Notifications\CustomerNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;


class UserTransactionController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'amount' => 'required|numeric',
        ]);
    
        // Fetch the sender's wallet
        $wallet = Wallet::where('user_id', auth()->user()->user_id)->first();
    
        // Validate if there's enough balance to deduct
        if (!$wallet || $wallet->Balance < $request->input('amount')) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }
    
        // Get the user receiver ID by phone number
        $user = User::where('phone_number', $request->input('phone_number'))->first();
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Deduct the balance
        $wallet->decrement('Balance', $request->input('amount'));
    
        // Create the transaction
        $transaction_id = UserTransaction::max('transaction_id') + 1;
        $transaction = UserTransaction::create([
            'transaction_id' => $transaction_id,
            'sender_id' => auth()->user()->user_id,
            'user_receiver_id' => $user->user_id,
            'amount' => $request->input('amount'),
        ]);
    
        Notification::send((auth()->user()), new CustomerNotification("Transfer Money",true,$request->input('amount')));
        Notification::send(($user), new CustomerNotification("Transfer Money",false,$request->input('amount')));

        return response()->json(['Time' => $transaction->created_at,'sender name'=>auth()->user()->Name,'receiver name'=>$user->Name,'phone number'=>$request->input('phone_number'),'amount'=>$request->input('amount')],200);
    }
    




    public function show(Request $request)
    {

    $sender_id = $request->input('sender_id');

    $query = UserTransaction::query();

    if ($sender_id !== null) {
        $query->where('sender_id', $sender_id);
    }

    $transactions = $query->get();

    return response()->json($transactions);

    }

    public function getResentTransfers()
    {
    // Get the authenticated user's ID
    $userId = auth()->user()->user_id;

    // Retrieve the latest three transactions for the authenticated user
    $latestTransactions = UserTransaction::where('sender_id', $userId)
                            ->latest()
                            ->take(3)
                            ->get();

    // Extract the receiver IDs from the transactions
    $receiverIds = $latestTransactions->pluck('user_receiver_id');

    // Retrieve the phone numbers of the users who received the transfers
    $resentTransfers = User::whereIn('user_id', $receiverIds)
                            ->get(['phone_number'])
                            ->pluck('phone_number');

    // Return JSON response containing the phone numbers
    return response()->json(['resent_transfers' => $resentTransfers],200);
    }


    public function getFavorateTransfers()
{
    // Get the authenticated user's ID
    $userId = auth()->user()->user_id;

    // Retrieve the user IDs to whom the authenticated user has transferred money most frequently
    $favorateTransfers = UserTransaction::where('sender_id', $userId)
                            ->select('user_receiver_id', DB::raw('count(*) as transfers_count'))
                            ->groupBy('user_receiver_id')
                            ->orderBy('transfers_count', 'desc')
                            ->take(2)
                            ->get();

    // Extract the receiver IDs from the frequent transfers
    $receiverIds = $favorateTransfers->pluck('user_receiver_id');

    // Retrieve the phone numbers of these users
    $favorateTransferUsers = User::whereIn('user_id', $receiverIds)->get(['phone_number'])->pluck('phone_number');

    // Return JSON response containing the phone numbers of the two most frequent receivers
    return response()->json(['favorate_transfers' => $favorateTransferUsers],200);
}


}

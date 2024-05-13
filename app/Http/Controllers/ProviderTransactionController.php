<?php

namespace App\Http\Controllers;

use App\Models\ProviderTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\ProviderWallet;
use App\Notifications\CustomerNotification;
use Illuminate\Support\Facades\Notification;

class ProviderTransactionController extends Controller
{

    public function store(Request $request)
{
    $request->validate([
        'provider_receiver_id' => 'required|string',
        'amount' => 'required|numeric',
    ]);

    // Fetch the sender's wallet
    $wallet = Wallet::where('user_id',auth()->user()->user_id)->first();

    // Validate if there's enough balance to deduct
    if (!$wallet || $wallet->Balance < $request->input('amount')) {
        return response()->json(['message' => 'Insufficient balance'], 400);
    }

    // Deduct the balance
    $wallet->decrement('Balance', $request->input('amount'));


    // Increment the provider's wallet balance by the transaction amount
    $providerWallet = ProviderWallet::where('ServiceProviderID', $request->input('provider_receiver_id'))->first();
    if (!$providerWallet) {
        return response()->json(['message' => 'Provider wallet not found'], 404);
    }
    $providerWallet->increment('Balance', $request->input('amount'));


    // Create the transaction
    $transaction_id = ProviderTransaction::max('transaction_id') + 1;
    $transaction = ProviderTransaction::create([
        'transaction_id' => $transaction_id,
        'sender_id' => auth()->user()->user_id,
        'provider_receiver_id' => $request->input('provider_receiver_id'),
        'amount' => $request->input('amount'),
    ]);

    // Fetch the updated wallet
    $updatedWallet = Wallet::where('user_id', auth()->user()->user_id)->first();

    Notification::send((auth()->user()), new CustomerNotification("buy to Service",true,$request->input('amount')));

    return response()->json(['Transaction' => $transaction, 'Updated Wallet' => $updatedWallet], 201);
}



    public function show(Request $request)
    {
        $sender_id = $request->input('sender_id');
    
        $query = ProviderTransaction::query();
    
        if ($sender_id !== null) {
            $query->where('sender_id', $sender_id);
        }
    
        $transactions = $query->get();
    
        return response()->json($transactions);
    }

}

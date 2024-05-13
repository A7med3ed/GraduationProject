<?php

namespace App\Http\Controllers;
use App\Models\ProviderBankTransaction;
use App\Models\ProviderWallet;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;

class ProviderWalletController extends Controller
{

    public function show()
    {
        // Retrieve the wallet associated with the specified user_id
        $wallet = ProviderWallet::findOrFail(auth()->user()->ServiceProviderID);
        $user=ServiceProvider::findOrFail(auth()->user()->ServiceProviderID);
        if ($wallet) {
            return response()->json(['Wallet'=>$wallet,'user'=>$user],200);
        } else {
            return response()->json(['message' => 'Wallet not found for the specified user'], 404);
        }

    }


    public function transferToBank(Request $request)
    {
        $request->validate([

            'bank_account_number' => 'required|string',
            'bank_name' => 'required|string',
            'receiver_name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $providerWallet = ProviderWallet::findOrFail(auth()->user()->ServiceProviderID);

        // Validate if there's enough balance in the provider's wallet
        if ($providerWallet->Balance < $request->amount) 
        {
            return response()->json(['message' => 'Insufficient balance in provider wallet'], 400);
        }

        // Decrement the provider's wallet balance
        $providerWallet->decrement('Balance', $request->amount);

      // Create the provider bank transaction
        $transaction_id = ProviderBankTransaction::max('transaction_id') + 1;
        $providerBankTransaction = ProviderBankTransaction::create([
        'transaction_id' => $transaction_id,
        'ServiceProviderID' => auth()->user()->ServiceProviderID,
        'bank_account_number' => $request->bank_account_number,
        'bank_name' => $request->bank_name,
        'receiver_name' => $request->receiver_name,
        'amount' => $request->amount,
    ]);

    return response()->json(['message' => 'Transfer successful', 'providerWallet' => $providerWallet,'Transaction'=>$providerBankTransaction],200);
} 

}
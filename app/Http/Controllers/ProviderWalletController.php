<?php

namespace App\Http\Controllers;

use App\Models\ProviderWallet;
use Illuminate\Http\Request;


class ProviderWalletController extends Controller
{

    public function show(Request $request)
    {
        // Retrieve the wallet associated with the specified user_id
        $wallet = ProviderWallet::findOrFail($request->ServiceProviderID);

        if ($wallet) {
            return response()->json([$wallet]);
        } else {
            return response()->json(['message' => 'Wallet not found for the specified user'], 404);
        }

    }


    public function transferToBank(Request $request)
    {
        $request->validate([
            'ServiceProviderID' => 'required|exists:provider_wallets,ServiceProviderID',
            'bank_account_number' => 'required|string',
            'bank_name' => 'required|string',
            'receiver_name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $providerWallet = ProviderWallet::findOrFail($request->ServiceProviderID);

        // Validate if there's enough balance in the provider's wallet
        if ($providerWallet->Balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance in provider wallet'], 400);
        }

        // Decrement the provider's wallet balance
        $providerWallet->decrement('Balance', $request->amount);

        return response()->json(['message' => 'Transfer successful', 'providerWallet' => $providerWallet], 200);
    }
    
}

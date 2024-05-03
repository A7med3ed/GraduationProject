<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        // Retrieve the wallet associated with the specified user_id
        $wallet = Wallet::where('user_id', $request->input('user_id'))->with('user')->first();
        
        if ($wallet) {
            return response()->json([$wallet]);
        } else {
            return response()->json(['message' => 'Wallet not found for the specified user'], 404);
        }
    }

    public function topupWallet(Request $request)
    {
    $wallet = Wallet::where('user_id', $request->input('user_id'))->first();
    
    if (!$wallet) {
        return response()->json(['message' => 'Wallet not found for the specified user'], 404);
    }

    // Validate the top-up amount
    if (!is_numeric($request->input('amount')) || $request->input('amount') <= 0) {
        return response()->json(['message' => 'Invalid top-up amount'], 400);
    }

    // Update the balance
    $wallet->increment('Balance', $request->input('amount'));

    // Return the updated wallet
    return response()->json($wallet, 200);

    }


}

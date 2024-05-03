<?php

namespace App\Http\Controllers;

use App\Models\CardTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;

class CardTransactionController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|string',
            'card_number' => 'required|string',
            'card_name' => 'required|string',
            'amount' => 'required|numeric',
        ]);
    
        // Fetch the sender's wallet
        $wallet = Wallet::where('user_id', $request->input('sender_id'))->first();
    
        // Validate if there's enough balance to deduct
        if (!$wallet || $wallet->Balance < $request->input('amount')) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }
    
        // Deduct the balance
        $wallet->decrement('Balance', $request->input('amount'));
    
        // Create the transaction
        $transaction_id = CardTransaction::max('transaction_id') + 1;
        $transaction = CardTransaction::create([
            'transaction_id' => $transaction_id,
            'sender_id' => $request->input('sender_id'),
            'card_number' => $request->input('card_number'),
            'card_name' => $request->input('card_name'),
            'amount' => $request->input('amount'),
        ]);
    
        // Fetch the updated wallet
        $updatedWallet = Wallet::where('user_id', $request->input('sender_id'))->first();
    
        return response()->json(['Transaction' => $transaction, 'Updated Wallet' => $updatedWallet], 201);
    }
    


    public function show(Request $request)
    {
        $sender_id = $request->input('sender_id');

        $query = CardTransaction::query();

        if ($sender_id !== null) {
            $query->where('sender_id', $sender_id);
        }

        $transactions = $query->get();

        return response()->json($transactions);
    }
    
}

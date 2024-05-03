<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;

class BankTransactionController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'sender_id' => 'required|string',
        'bank_account_number' => 'required|string',
        'bank_name' => 'required|string',
        'receiver_name' => 'required|string',
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
    $transaction_id = BankTransaction::max('transaction_id') + 1;
    $transaction = BankTransaction::create([
        'transaction_id' => $transaction_id,
        'sender_id' => $request->input('sender_id'),
        'bank_account_number' => $request->input('bank_account_number'),
        'bank_name' => $request->input('bank_name'),
        'receiver_name' => $request->input('receiver_name'),
        'amount' => $request->input('amount'),
    ]);

    // Fetch the updated wallet
    $updatedWallet = Wallet::where('user_id', $request->input('sender_id'))->first();

    return response()->json(['Transaction' => $transaction, 'Updated Wallet' => $updatedWallet], 201);
}


    public function show(Request $request)
    {
        $sender_id = $request->input('sender_id');

        $query = BankTransaction::query();

        if ($sender_id !== null) {
            $query->where('sender_id', $sender_id);
        }

        $transactions = $query->get();

        return response()->json($transactions);
    }


}

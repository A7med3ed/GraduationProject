<?php

namespace App\Http\Controllers;

use App\Models\UserTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;

class UserTransactionController extends Controller
{

    public function store(Request $request)
{
    $request->validate([
        'sender_id' => 'required|string',
        'user_receiver_id' => 'required|string',
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
    $transaction_id = UserTransaction::max('transaction_id') + 1;
    $transaction = UserTransaction::create([
        'transaction_id' => $transaction_id,
        'sender_id' => $request->input('sender_id'),
        'user_receiver_id' => $request->input('user_receiver_id'),
        'amount' => $request->input('amount'),
    ]);

    // Fetch the updated wallet
    $updatedWallet = Wallet::where('user_id', $request->input('sender_id'))->first();

    return response()->json(['Transaction' => $transaction, 'Updated Wallet' => $updatedWallet], 201);
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

}

<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Notifications\CustomerNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;


class BankTransactionController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'bank_account_number' => 'required|string',
        'bank_name' => 'required|string',
        'receiver_name' => 'required|string',
        'amount' => 'required|numeric',
    ]);

    // Fetch the sender's wallet
    $wallet = Wallet::where('user_id', auth()->user()->user_id)->first();

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
        'sender_id' => auth()->user()->user_id,
        'bank_account_number' => $request->input('bank_account_number'),
        'bank_name' => $request->input('bank_name'),
        'receiver_name' => $request->input('receiver_name'),
        'amount' => $request->input('amount'),
    ]);

    // Fetch the updated wallet
    $updatedWallet = Wallet::where('user_id', auth()->user()->user_id)->first();

    Notification::send((auth()->user()), new CustomerNotification("bank",true,$request->input('amount')));

    return response()->json(['Time' => $transaction->created_at,'sender name'=>auth()->user()->Name,'bank_account_number'=>$request->input('bank_account_number'),'amount'=>$request->input('amount')],200);
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


    public function getRecentTransactions()
    {
        // Get the authenticated user's ID
        $userId = auth()->user()->user_id;
    
        // Retrieve the bank account numbers for the latest three transactions for the authenticated user
        $recentBankAccountNumbers = BankTransaction::where('sender_id', $userId)
                                ->latest()
                                ->take(3)
                                ->pluck('bank_account_number');
    
        // Return JSON response containing the recent bank account numbers
        return response()->json(['recent_bank_account_numbers' => $recentBankAccountNumbers]);
    }
    
    public function getFavorateTransactions()
    {
    // Get the authenticated user's ID
    $userId = auth()->user()->user_id;

    // Retrieve the bank account numbers for the two most frequent transactions
    $favorateBankAccountNumbers = BankTransaction::where('sender_id', $userId)
                            ->select('bank_account_number', DB::raw('count(*) as transaction_count'))
                            ->groupBy('bank_account_number')
                            ->orderBy('transaction_count', 'desc')
                            ->take(2)
                            ->pluck('bank_account_number');

    // Return JSON response containing the frequent bank account numbers
    return response()->json(['favorate_bank_account_numbers' => $favorateBankAccountNumbers]);
    }


}

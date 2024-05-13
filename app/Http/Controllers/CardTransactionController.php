<?php

namespace App\Http\Controllers;

use App\Models\CardTransaction;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Notifications\CustomerNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class CardTransactionController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string',
            'card_name' => 'required|string',
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
        $transaction_id = CardTransaction::max('transaction_id') + 1;
        $transaction = CardTransaction::create([
            'transaction_id' => $transaction_id,
            'sender_id' => auth()->user()->user_id,
            'card_number' => $request->input('card_number'),
            'card_name' => $request->input('card_name'),
            'amount' => $request->input('amount'),
        ]);
    
        // Fetch the updated wallet
        $updatedWallet = Wallet::where('user_id', auth()->user()->user_id)->first();
    
        Notification::send((auth()->user()), new CustomerNotification("Card",true,$request->input('amount')));

        return response()->json(['Time' => $transaction->created_at,'sender name'=>auth()->user()->Name,'card_number'=>$request->input('card_number'),'amount'=>$request->input('amount')],200);
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
    

    public function getRecentTransactions()
    {
    // Get the authenticated user's ID
    $userId = auth()->user()->user_id;

    // Retrieve the card numbers for the latest three transactions for the authenticated user
    $recentCardNumbers = CardTransaction::where('sender_id', $userId)
                            ->latest()
                            ->take(3)
                            ->pluck('card_number');

    // Return JSON response containing the recent card numbers
    return response()->json(['recent_card_numbers' => $recentCardNumbers]);
    }


    public function getFavorateTransactions()
    {
    // Get the authenticated user's ID
    $userId = auth()->user()->user_id;

    // Retrieve the card numbers for the two most frequent transactions
    $favorateCardNumbers = CardTransaction::where('sender_id', $userId)
                            ->select('card_number', DB::raw('count(*) as transaction_count'))
                            ->groupBy('card_number')
                            ->orderBy('transaction_count', 'desc')
                            ->take(2)
                            ->pluck('card_number');

    // Return JSON response containing the frequent card numbers
    return response()->json(['favorate_card_numbers' => $favorateCardNumbers]);
    }



}

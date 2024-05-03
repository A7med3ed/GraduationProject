<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    private $kmsController;

    public function __construct(KMSController $kmsController)
    {
        $this->kmsController = $kmsController;
    }


    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'account_number' => 'required|string|unique:bank_accounts',
            'account_holder_name' => 'required|string',
            'bank_name' => 'required|string',
            'account_type' => 'required|in:debit,credit',
            'user_id' => 'required|string|exists:users,user_id',
        ]);

        // Encrypt the account number
        $encryptedAccountNumber = $this->kmsController->encryptData($request->input('account_number'), $request->input('user_id'));

        // Create a new bank account
        $bankAccount = BankAccount::create([
            'account_number' => $encryptedAccountNumber,
            'account_holder_name' => $request->input('account_holder_name'),
            'bank_name' => $request->input('bank_name'),
            'account_type' => $request->input('account_type'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json($bankAccount, 201);
    }

    public function show($id)
    {
        // Retrieve a single bank account by id
        $bankAccount = BankAccount::findOrFail($id);

        // Decrypt the account number
        $bankAccount->account_number = $this->kmsController->decryptData($bankAccount->account_number, $bankAccount->user_id);

        return response()->json($bankAccount);
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'account_number' => 'required|string|unique:bank_accounts,account_number,' . $id,
            'account_holder_name' => 'required|string',
            'bank_name' => 'required|string',
            'account_type' => 'required|in:debit,credit',
            'user_id' => 'required|string|exists:users,user_id',
        ]);

        // Find the bank account
        $bankAccount = BankAccount::findOrFail($id);

        // Encrypt the account number
        $encryptedAccountNumber = $this->kmsController->encryptData($request->input('account_number'), $request->input('user_id'));

        // Update the bank account
        $bankAccount->update([
            'account_number' => $encryptedAccountNumber,
            'account_holder_name' => $request->input('account_holder_name'),
            'bank_name' => $request->input('bank_name'),
            'account_type' => $request->input('account_type'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json($bankAccount, 200);
    }
}

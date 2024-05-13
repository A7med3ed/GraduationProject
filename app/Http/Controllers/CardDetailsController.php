<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardDetails;


class CardDetailsController extends Controller
{
    private $kmsController;

    public function __construct(KMSController $kmsController)
    {
        $this->kmsController = $kmsController;
    }

    public function index()
    {
        // Retrieve all cards
        $cards =CardDetails::all();
        $card_numbers=$cards->pluck('card_number');
        $card_type=$cards->pluck('card_type');
        foreach ($card_numbers as $card_numbers) {
        $card_numbers=$this->kmsController->decryptData($card_numbers, auth()->user()->user_id);
        }
        return response()->json(['card numbers'=>$card_numbers,'card type'=>$card_type],200);
    }

    public function store(Request $request)
    {
    // Validate the incoming request data
    $request->validate([
        'card_holder_name' => 'required|string',
        'card_type' => 'required|in:debit,credit',
        'card_number' => 'required|string|regex:/^\d{16}$/',
        'expiry_date' => 'required|date|after_or_equal:today',
        'cvv' => 'required|string|regex:/^\d{3}$/',
        'user_id' => 'required|exists:bank_accounts,user_id'
    ]);

    // Encrypt card number and CVV
    $encryptedCardNumber =$this->kmsController->encryptCard($request->input('card_number'), $request->input('user_id'));
    $encryptedCVV = $this->kmsController->encryptCard($request->input('cvv'), $request->input('user_id'));

    $cardId= CardDetails::max('card_id') + 1;

    // Create the card details record
    $cardDetail = CardDetails::create([
        'card_id'=>$cardId,
        'card_holder_name' => $request->input('card_holder_name'),
        'card_type' => $request->input('card_type'),
        'card_number' => $encryptedCardNumber,
        'expiry_date' => $request->input('expiry_date'),
        'cvv' => $encryptedCVV,
        'user_id' => $request->input('user_id')
    ]);

    return response()->json($cardDetail, 201);
    }


    public function showCards()
    {
    // Retrieve card details for the given user ID
    $cardDetails = CardDetails::where('user_id', auth()->user()->user_id)->get();


    // Decrypt card number and CVV for each card detail
    foreach ($cardDetails as $cardDetail) { 
        $cardDetail->card_number = $this->kmsController->decryptData($cardDetail->card_number, auth()->user()->user_id);
    }

    unset($cardDetails['cvv']);

    return response()->json($cardDetails, 200);
    }

    public function showCvv($card_id)
    {
    // Retrieve the card detail by card ID
    $cardDetail = CardDetails::findOrFail($card_id);

    // Decrypt the CVV for the card detail
    $decryptedCvv = $this->kmsController->decryptData($cardDetail->cvv, $cardDetail->user_id);

    return response()->json(['cvv' => $decryptedCvv], 200);
    }


    public function update(Request $request, $id)
    {
    // Validate the incoming request data
    $request->validate([
        'card_holder_name' => 'sometimes|required|string',
        'card_type' => 'sometimes|required|in:debit,credit',
        'card_number' => 'sometimes|required|string|regex:/^\d{16}$/',
    ]);

    // Find the card detail
    $cardDetail = CardDetails::findOrFail($id);

    // Encrypt the cardNumber if it's present in the request
    if ($request->has('card_number')) {
        $request->merge(['card_number' => $this->kmsController->encryptCard($request->input('card_number'),$cardDetail->user_id)]);
    }

    /// Get the request data with non-null values
    $requestData = array_filter($request->all());

    // Update only the existing fields in the $cardDetail
    $cardDetail->fill($requestData)->save();
    

    return response()->json($cardDetail, 200);
    }



    public function destroy($card_id)
    {
        $cardDetail = CardDetails::findOrFail($card_id);
        $cardDetail->delete();

        return response()->json(null, 204);
    }
}

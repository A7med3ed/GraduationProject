<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\Ticket_CustomerData;
use App\Models\TicketBooking;
use Illuminate\Http\Request;

class TicketCustomerDataController extends Controller
{
    public function index()
    {
        $ticketCustomerData = Ticket_CustomerData::all();
        return response()->json($ticketCustomerData, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:ticket_bookings,Service_id',
            'number_of_Tickets' => 'required|integer',
        ]);

        // Retrieve extra fields from associated TicketBooking
        $ticketBooking = TicketBooking::findOrFail($request->input('service_id'));
        $extraFields = $ticketBooking->extra_fields;

        // Create Ticket_CustomerData
        $ticketCustomerData = Ticket_CustomerData::create([
            'user_id' => $request->input('user_id'),
            'service_id' => $request->input('service_id'),
            'number_of_Tickets' => $request->input('number_of_Tickets'),
            'extra_fields' => $extraFields,
        ]);

        return response()->json($ticketCustomerData, 201);
    }

    public function show($id)
    {
    $ticketCustomerData = DB::connection('mongodb')->collection('Ticket_CustomerData')->where('_id',$id)->first();

    // Check if data is found
    if (!$ticketCustomerData) {
        return response()->json(['message' => 'Ticket customer data not found'], 404);
    }

    return response()->json($ticketCustomerData, 200);
    }

    
    public function destroy($id)
    {
    DB::connection('mongodb')->collection('Ticket_CustomerData')->where('_id', $id)->delete();
    return response()->json(null, 204);
    }


}

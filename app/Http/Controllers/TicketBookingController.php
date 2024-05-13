<?php

namespace App\Http\Controllers;

use App\Models\TicketBooking;
use Illuminate\Http\Request;

class TicketBookingController extends Controller
{
    public function index()
    {
        // Retrieve all ticket bookings
        $ticketBookings = TicketBooking::all();
        return response()->json($ticketBookings, 200);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'EventName' => 'required|string|max:255',
            'EventDate' => 'required|date',
            'NumberofTickets' => 'required|integer',
            'Price' => 'required|numeric',
            'Place' => 'required|string|max:255',
            'extra_fields' => 'nullable|array',
        ]);
        $Service_id = TicketBooking::max('Service_id') + 1;
        $ticketBooking = TicketBooking::create([
            'Service_id'=>$Service_id,
            'ServiceProviderID'=>auth()->user()->ServiceProviderID,
            'Support_Contact_Number' => $request->input('Support_Contact_Number'),
            'EventName' => $request->input('EventName'),
            'EventDate' => $request->input('EventDate'),
            'NumberofTickets' => $request->input('NumberofTickets'),
            'Price' => $request->input('Price'),
            'Place' => $request->input('Place'),
            'icon' => $request->input('icon'),
            'extra_fields' => $request->input('extra_fields'),
        ]); 
        return response()->json($ticketBooking, 200);
    }

    public function show($id)
    {
        // Find the ticket booking by ID
        $ticketBooking = TicketBooking::findOrFail($id);
        return response()->json($ticketBooking, 200);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'EventName' => 'sometimes|required|string|max:255',
            'EventDate' => 'sometimes|required|date',
            'NumberofTickets' => 'sometimes|required|integer',
            'Price' => 'sometimes|required|numeric',
            'Place' => 'sometimes|required|string|max:255',
            'extra_fields' => 'nullable|array',
        ]);

        // Find the ticket booking by ID
        $ticketBooking = TicketBooking::findOrFail($id);

        /// Get the request data with non-null values
        $requestData = array_filter($request->all());

        // Update only the existing fields in the ticket booking
        $ticketBooking->fill($requestData)->save();

        return response()->json($ticketBooking, 200);
    }

    public function destroy($id)
    {
        // Find the ticket booking by ID and delete it
        $ticketBooking = TicketBooking::findOrFail($id);
        $ticketBooking->delete();
        return response()->json(null, 204);
    }
}
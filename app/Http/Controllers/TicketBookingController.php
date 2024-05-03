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
            'ServiceProviderID' => 'required|string',
            'EventName' => 'required|string|max:255',
            'EventDate' => 'required|date',
            'NumberofTickets' => 'required|integer',
            'Price' => 'required|numeric',
            'Place' => 'required|string|max:255',
            'extra_fields' => 'nullable|array',
        ]);

        // Create a new ticket booking
        $ticketBooking = TicketBooking::create($request->all());
        return response()->json($ticketBooking, 201);
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

<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\BookingForm;
use App\Admin\Tables\BookingTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookingRequest;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(BookingTable $table)
    {
        return $table->renderTable();
    }

    public function show(Booking $booking)
    {
        return BookingForm::make()
            ->createWithModel($booking->loadMissing('bookingServices.staffReview'))
            ->renderForm();
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }

    public function export(Request $request)
    {
        $booking = Booking::query()
            ->with('bookingServices.staff.user')
            ->findOrFail($request->input('id'));

        $pdf = PDF::loadView('admin.templates.invoice', compact('booking'));

        $fileName = sprintf(
            'hoa-don-%s-%s-%s.pdf',
            $booking->booking_code,
            now()->format('Ymd'),
            Str::slug($booking->customer_name)
        );

        return $pdf->download($fileName);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Booking deleted successfully',
        ]);
    }
}

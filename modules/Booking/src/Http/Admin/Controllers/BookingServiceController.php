<?php

namespace Modules\Booking\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookingService;
use Modules\Booking\Admin\Forms\BookingServiceForm;
use Modules\Booking\Admin\Tables\BookingServiceTable;
use Modules\Booking\Http\Requests\BookingServiceRequest;

class BookingServiceController extends Controller
{
    public function index(BookingServiceTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return BookingServiceForm::make()->renderForm();
    }

    public function store(BookingServiceRequest $request)
    {
        BookingService::create($request->validated());

        return redirect()->route('admin.booking-services.index')
            ->with('success', 'BookingService created successfully.');
    }

    public function show(BookingService $bookingService)
    {
        return BookingServiceForm::make()->createWithModel($bookingService)->renderForm();
    }

    public function edit(BookingService $bookingService)
    {
        return BookingServiceForm::make()->createWithModel($bookingService)->renderForm();
    }

    public function update(BookingServiceRequest $request, BookingService $bookingService)
    {
        $bookingService->update($request->validated());

        return redirect(request()->input('_previous_url') ?? route('admin.booking-services.index'))->with('success', 'BookingService updated successfully.');
    }

    public function destroy(BookingService $bookingService)
    {
        $bookingService->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'BookingService deleted successfully',
        ]);
    }
}

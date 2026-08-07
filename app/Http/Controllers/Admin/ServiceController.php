<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Tables\ServiceTable;
use App\Forms\ServiceForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(ServiceTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return ServiceForm::make()->renderForm();
    }

    public function store(ServiceRequest $request)
    {
        Service::query()->create($request->validated());

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        return ServiceForm::make()
            ->createWithModel($service)
            ->renderForm();
    }

    public function edit(Service $service)
    {
        return ServiceForm::make()->createWithModel($service)->renderForm();
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return redirect(request()->input('_previous_url') ?? route('admin.services.index'))->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Service deleted successfully',
        ]);
    }
}

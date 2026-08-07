<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateCustomerAction;
use App\Admin\Forms\CustomerForm;
use App\Admin\Tables\CustomerTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(CustomerTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return CustomerForm::make()->renderForm();
    }

    public function store(CustomerRequest $request, CreateCustomerAction $action)
    {
        $action->handle($request->validated());

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return CustomerForm::make()->createWithModel($customer)->renderForm();
    }

    public function edit(Customer $customer)
    {
        return CustomerForm::make()->createWithModel($customer)->renderForm();
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Customer deleted successfully',
        ]);
    }
}

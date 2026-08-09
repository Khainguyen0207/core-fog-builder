<?php

namespace Modules\Customers\Http\Admin\Controllers;

use App\Actions\CreateCustomerAction;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Modules\Customers\Admin\Forms\CustomerForm;
use Modules\Customers\Admin\Tables\CustomerTable;
use Modules\Customers\Http\Requests\CustomerRequest;

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

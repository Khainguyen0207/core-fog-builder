<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\TransactionForm;
use App\Admin\Tables\TransactionTable;
use App\Enums\TransactionStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(TransactionTable $table)
    {
        return $table->renderTable();
    }

    public function show(Transaction $transaction)
    {
        return TransactionForm::make()
            ->createWithModel($transaction->loadMissing('paymentSetting'))
            ->renderForm();
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(TransactionStatusEnum::cases())],
        ]);

        $transaction->update($validated);

        return redirect()->back()->with('success', 'Transaction updated successfully.');
    }
}

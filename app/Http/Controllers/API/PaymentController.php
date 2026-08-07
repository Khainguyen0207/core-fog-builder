<?php

namespace App\Http\Controllers\API;

use App\Actions\CheckTransactionAction;
use App\Actions\CreateTransactionAction;
use App\Enums\PaymentMethodEnum;
use App\Enums\TransactionStatusEnum;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentController
{
    public function createTransaction(TransactionRequest $request)
    {
        $amount = $request->input('amount');
        $name = $request->input('customer_name');
        $phone = $request->input('customer_phone');

        $transaction = (new CreateTransactionAction($amount, $name, $phone))->handle();

        return response()->json([
            'error' => false,
            'data' => $transaction,
            'message' => 'Tạo giao dịch thành công',
        ]);
    }

    public function getTransactionStatus($transactionCode, CheckTransactionAction $action)
    {
        $transaction = Transaction::query()->where('transaction_code', $transactionCode)->firstOrFail();
        $provider = $action->getProviderService($transaction->provider_code->getValue());
        $config = $provider->getPaymentConfig();

        if (count($config) === 0) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy cấu hình thanh toán',
            ]);
        }

        $accountNumber = $config['account_number'];
        $bankName = $config['bank_name'];
        $receiverName = $config['receiver_name'];

        return response()->json([
            'error' => false,
            'data' => [
                'token' => $transaction->token,
                'qr_url' => "https://img.vietqr.io/image/MB-{$accountNumber}-qr.png?amount={$transaction->amount}&addInfo={$transaction->transaction_code}&accountName={$receiverName}",
                'amount' => $transaction->amount,
                'receiver_name' => $receiverName,
                'bank_name' => $bankName,
                'bank_name_label' => get_bank_name_label($bankName),
                'account_number' => $accountNumber,
                'status' => Str::lower($transaction->status->getValue() ?? 'pending'),
                'expired_at' => $transaction->expired_at,
                'transaction_code' => $transaction->transaction_code,
            ],
            'message' => 'Lấy phiên thanh toán thành công.',
        ]);
    }

    public function getPaymentMethods()
    {
        return response()->json([
            'error' => false,
            'data' => PaymentMethodEnum::labels(),
            'message' => 'Lấy phương thức thanh toán thành công.',
        ]);
    }

    public function changeTransactionPaymentMethod(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required|exists:transactions,transaction_code',
            'payment_method' => ['required', Rule::in(PaymentMethodEnum::cases())],
        ]);

        $transaction = Transaction::query()
            ->with(['booking', 'booking.bookingServices'])
            ->where('transaction_code', $request->transaction_code)
            ->firstOrFail();

        $transaction->update([
            'payment_method' => $request->payment_method,
            'status' => TransactionStatusEnum::FAILED,
        ]);

        $transaction->booking->update([
            'payment_method' => $request->payment_method,
        ]);

        return response()->json([
            'error' => false,
            'data' => BookingResource::make($transaction->booking ?? []),
            'message' => 'Thay đổi phương thức thanh toán thành công',
        ]);
    }

    public function renewTransaction(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required|exists:transactions,transaction_code',
        ]);

        $transaction = Transaction::query()->where('transaction_code', $request->transaction_code)->firstOrFail();
        $status = $transaction->status->getValue();

        if (in_array($status, [TransactionStatusEnum::PENDING, TransactionStatusEnum::EXPIRED])) {
            $transaction->expired_at = now()->addMinutes(10);

            $transaction->save();
        }

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Gia hạn giao dịch thành công.',
        ]);
    }

    public function checkTransactionByToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $transaction = Transaction::query()
            ->with('booking.bookingServices.service', 'booking.bookingServices.staff.user', 'booking.transaction')
            ->where('token', $request->input('token'))
            ->whereIn('status', [TransactionStatusEnum::PENDING, TransactionStatusEnum::COMPLETED])
            ->first();

        if (! $transaction) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Token không hợp lệ hoặc giao dịch không tồn tại.',
            ], 404);
        }

        if ($transaction->status->getValue() === TransactionStatusEnum::COMPLETED) {
            return response()->json([
                'error' => false,
                'message' => 'Giao dịch đã được thanh toán.',
            ], 204);
        }

        return response()->json([
            'error' => false,
            'data' => BookingResource::make($transaction->booking ?? []),
            'message' => 'Lấy thông tin giao dịch thành công.',
        ]);
    }
}

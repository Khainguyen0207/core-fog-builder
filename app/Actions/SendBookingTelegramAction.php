<?php

namespace App\Actions;

use App\Facades\SettingHelper;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class SendBookingTelegramAction
{
    public function handle(Booking $booking): void
    {
        try {
            $chatId = SettingHelper::get('telegram_chat_id');

            if (empty($chatId)) {
                Log::error('Telegram chat ID not found');
                return;
            }

            $booking->loadMissing('bookingServices.staff');

            $message = $this->buildMessage($booking);

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function buildMessage(Booking $booking): string
    {
        $statusLabel = $booking->status?->getLabel() ?? 'N/A';
        $paymentLabel = $booking->payment_method?->getLabel() ?? 'N/A';

        $lines = [
            "🔔 <b>ĐƠN HÀNG MỚI</b>",
            "",
            "📋 <b>Mã đặt lịch:</b> <code>{$booking->booking_code}</code>",
            "👤 <b>Khách hàng:</b> {$booking->customer_name}",
            "📞 <b>SĐT:</b> {$booking->customer_phone}",
        ];

        if ($booking->notify_email) {
            $lines[] = "📧 <b>Email:</b> {$booking->notify_email}";
        }

        $lines[] = "";
        $lines[] = "📅 <b>Lịch hẹn:</b> " . $booking->scheduled_start?->format('H:i d/m/Y');
        $lines[] = "⏱ <b>Dự kiến kết thúc:</b> " . $booking->estimated_end?->format('H:i d/m/Y');
        $lines[] = "⏳ <b>Thời lượng:</b> {$booking->total_duration} phút";
        $lines[] = "📌 <b>Trạng thái:</b> {$statusLabel}";
        $lines[] = "💳 <b>Thanh toán:</b> {$paymentLabel}";

        // Services
        $lines[] = "";
        $lines[] = "🔧 <b>Dịch vụ:</b>";

        foreach ($booking->bookingServices as $index => $bs) {
            $num = $index + 1;
            $staffName = $bs->staff?->name ?? 'Chưa phân công';
            $servicePrice = number_format($bs->price, 0, ',', '.');
            $lines[] = "  {$num}. {$bs->service_name} — {$servicePrice}đ ({$bs->duration} phút) — NV: {$staffName}";
        }

        // Pricing
        $lines[] = "";
        $price = number_format($booking->price ?? 0, 0, ',', '.');
        $totalPrice = number_format($booking->total_price ?? 0, 0, ',', '.');

        $lines[] = "💰 <b>Giá gốc:</b> {$price}đ";

        if ($booking->discount > 0) {
            $discount = number_format($booking->discount, 0, ',', '.');
            $lines[] = "🏷 <b>Giảm giá:</b> -{$discount}đ";

            if ($booking->coupon_code) {
                $lines[] = "🎟 <b>Mã giảm giá:</b> <code>{$booking->coupon_code}</code>";
            }
        }

        $lines[] = "✅ <b>Tổng thanh toán:</b> <b>{$totalPrice}đ</b>";

        if ($booking->note) {
            $lines[] = "";
            $lines[] = "📝 <b>Ghi chú:</b> {$booking->note}";
        }

        $lines[] = "";
        $lines[] = "🕐 " . now()->format('H:i d/m/Y');

        return implode("\n", $lines);
    }
}

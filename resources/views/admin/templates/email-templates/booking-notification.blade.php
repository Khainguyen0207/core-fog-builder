<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông báo lịch hẹn {{ $booking->booking_code }}</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial, Helvetica, sans-serif;">
    <div
        style="display:none;font-size:0;font-weight:600;line-height:0;opacity:0;max-height:0;overflow:hidden;mso-hide:all;">
        Lịch hẹn của bạn lúc {{ \Carbon\Carbon::parse($booking->scheduled_start)->format('H:i') }}
        ngày {{ \Carbon\Carbon::parse($booking->scheduled_start)->format('d/m/Y') }} đã được ghi nhận.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background-color:#f4f6f8;padding:26px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="max-width:600px;background-color:#ffffff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 12px 32px rgba(15,23,42,0.08);overflow:hidden;">


                    <tr>
                        <td style="padding:22px 28px 40px 28px;font-family:Arial, Helvetica, sans-serif;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="padding:0;text-align:left;">
                                        <span
                                            style="display:inline-block;padding:7px 12px;border-radius:8px;background-color:#dcfce7;color:#166534;font-size:12px;letter-spacing:0.6px;font-weight:bold;">
                                            {{ $appName }}
                                        </span>
                                    </td>
                                    <td
                                        style="padding:0;text-align:right;font-size:12px;color:#94a3b8;letter-spacing:0.5px;">
                                        Booking Notification
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding:0 28px 10px 28px;font-family:Arial, Helvetica, sans-serif;text-align:left;">
                            <h1 style="margin:0;font-size:27px;line-height:1.35;color:#0f172a;letter-spacing:0.25px;">
                                Thông báo lịch hẹn của bạn
                            </h1>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding:0 28px 18px 28px;font-family:Arial, Helvetica, sans-serif;text-align:left;">
                            <p style="margin:0;font-size:15px;line-height:1.7;color:#334155;">
                                Xin chào <strong>{{ $booking->customer_name }}</strong>,<br>
                                Lịch hẹn của bạn đã được ghi nhận. Dưới đây là thông tin chi tiết:
                            </p>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding:0 28px 22px 28px;font-family:Arial, Helvetica, sans-serif;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;">
                                <tr>
                                    <td style="padding:16px;text-align:left;">
                                        <p style="margin:0 0 8px 0;font-size:14px;line-height:1.55;color:#475569;">
                                            <strong>Mã lịch hẹn:</strong> {{ $booking->booking_code }}
                                        </p>

                                        <p style="margin:0 0 8px 0;font-size:14px;line-height:1.55;color:#475569;">
                                            <strong>Thời gian:</strong>
                                            {{ \Carbon\Carbon::parse($booking->scheduled_start)->format('H:i') }}
                                            ngày {{ \Carbon\Carbon::parse($booking->scheduled_start)->format('d/m/Y') }}
                                        </p>

                                        @if (!empty($booking->total_duration) && (int) $booking->total_duration > 0)
                                            <p style="margin:0 0 8px 0;font-size:14px;line-height:1.55;color:#475569;">
                                                <strong>Thời lượng dự kiến:</strong>
                                                {{ (int) $booking->total_duration }}
                                                phút
                                            </p>
                                        @endif

                                        <p style="margin:0 0 8px 0;font-size:14px;line-height:1.55;color:#475569;">
                                            <strong>Biển số:</strong> {{ $booking->plate_number }}
                                        </p>

                                        <p style="margin:0 0 8px 0;font-size:14px;line-height:1.55;color:#475569;">
                                            <strong>Tổng chi phí:</strong>
                                            {{ number_format((float) $booking->total_price, 0, ',', '.') }} VND
                                        </p>

                                        <p style="margin:0 0 0 0;font-size:14px;line-height:1.55;color:#475569;">
                                            <strong>Phương thức thanh toán:</strong>
                                            {!! $booking->payment_method->toHtml() !!}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    @if (!empty($booking->note))
                        <tr>
                            <td
                                style="padding:0 28px 18px 28px;font-family:Arial, Helvetica, sans-serif;text-align:left;">
                                <p style="margin:0;font-size:14px;line-height:1.6;color:#334155;">
                                    <strong>Ghi chú:</strong> {{ $booking->note }}
                                </p>
                            </td>
                        </tr>
                    @endif


                    <tr>
                        <td style="padding:0 28px 22px 28px;font-family:Arial, Helvetica, sans-serif;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color:#eef2ff;border:1px solid #cbd5ff;border-radius:12px;">
                                <tr>
                                    <td style="padding:14px 16px;text-align:left;">
                                        <p style="margin:0;font-size:14px;line-height:1.55;color:#1e40af;">
                                            Vui lòng đến sớm 5–10 phút để được phục vụ đúng giờ.
                                            Nếu bạn cần thay đổi lịch hẹn, hãy liên hệ hỗ trợ.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding:0 28px 12px 28px;font-family:Arial, Helvetica, sans-serif;text-align:left;">
                            <p style="margin:6px 0 0 0;font-size:16px;line-height:1.6;color:#475569;">
                                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của
                                <span style="font-weight:bold;color:rgba(0, 159, 0, 0.88);">{{ $appName }}</span>.
                            </p>
                        </td>
                    </tr>


                    <tr>
                        <td
                            style="padding:0 28px 24px 28px;font-family:Arial, Helvetica, sans-serif;border-top:1px solid #e5e7eb;text-align:left;">
                            <p style="margin:12px 0 2px 0;font-size:13px;line-height:1.6;color:#94a3b8;">
                                © 2026 {{ $appName }}. All rights reserved.
                            </p>
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#94a3b8;">
                                Hỗ trợ: {{ $support }}
                                @if (!empty($booking->customer_phone))
                                    | Hotline: {{ $booking->customer_phone }}
                                @endif
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>

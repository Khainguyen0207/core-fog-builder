@php use App\Enums\BaseEnum; @endphp
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Hóa đơn #{{ $booking->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .wrap {
            width: 100%;
        }


        .topbar {
            width: 100%;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .brand {
            display: inline-block;
            vertical-align: middle;
        }

        .brand .name {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .brand .tagline {
            margin: 3px 0 0;
            font-size: 11px;
            color: #666;
        }

        .logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            vertical-align: middle;
            margin-right: 10px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .meta-line {
            margin: 3px 0 0;
            font-size: 11px;
            color: #666;
        }

        .meta-line b {
            color: #111;
        }


        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0d6efd;
            margin: 12px 0 8px;
        }


        .box {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 10px;
        }

        .grid {
            width: 100%;
        }

        .col {
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }

        .gap {
            width: 2%;
            display: inline-block;
        }


        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #e5e5e5;
            padding: 8px;
            vertical-align: top;
        }

        .table th {
            background: #f5f7fb;
            text-align: left;
            font-weight: 700;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: #666;
        }

        .nowrap {
            white-space: nowrap;
        }


        .totals {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .totals td {
            padding: 6px 8px;
        }

        .totals .label {
            text-align: right;
            color: #666;
        }

        .totals .value {
            text-align: right;
            width: 170px;
        }

        .totals .grand {
            font-weight: 800;
            font-size: 13px;
            border-top: 2px solid #111;
            padding-top: 10px;
        }


        .footer {
            margin-top: 16px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .note {
            margin-top: 6px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="wrap">


        <table class="topbar">
            <tr>
                <td>
                    @php
                        $logoPath = public_path('assets/img/avatars/default-avatar.png');
                    @endphp

                    @if (file_exists($logoPath))
                        <img class="logo" src="{{ $logoPath }}" alt="Logo" style="width: 50px; height: 50px;">
                    @endif

                    <span class="brand">
                        <div class="name">{{ config('app.brand_name', 'Cửa hàng sửa xe') }}</div>
                        <div class="tagline">{{ config('app.brand_tagline', 'Bảo dưỡng nhanh, chạy bền lâu') }}</div>
                        <div class="tagline">{{ config('app.url', 'https://khai.name.vn/') }}</div>
                    </span>
                </td>

                <td class="invoice-meta">
                    <div class="invoice-title">HÓA ĐƠN DỊCH VỤ</div>
                    <div class="meta-line">Mã hóa đơn: <b>#{{ $booking->id }}</b></div>
                    <div class="meta-line">Ngày tạo: <b>{{ optional($booking->created_at)->format('d/m/Y H:i') }}</b>
                    </div>
                </td>
            </tr>
        </table>


        <div class="grid">
            <div class="col">
                <div class="section-title">Thông tin cửa hàng</div>
                <table class="table">
                    <tr>
                        <th style="width:40%;">Tên cửa hàng</th>
                        <td>{{ config('app.brand_name', 'Cửa hàng sửa xe') }}</td>
                    </tr>
                    <tr>
                        <th>Hotline</th>
                        <td>{{ config('app.brand_phone', '0123 456 789') }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ config('app.brand_email', 'support@fogmailman.com') }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ config('app.brand_address', '123 Đường ABC, Quận 2, TP. HCM') }}</td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td>{{ config('app.url', 'https://khai.name.vn/') }}</td>
                    </tr>
                </table>

                <div class="note">
                    (Hóa đơn này được xuất từ hệ thống. Vui lòng giữ lại để đối chiếu khi cần.)
                </div>
            </div>
            <div class="col">
                <div class="section-title">Thông tin đặt lịch</div>
                <table class="table">
                    <tr>
                        <th style="width:40%;">Mã đặt lịch</th>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <th>Khách hàng</th>
                        <td>{{ $booking->customer_name ?? '_' }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $booking->customer_phone ?? '_' }}</td>
                    </tr>
                    <tr>
                        <th>Biển số</th>
                        <td>{{ $booking->plate_number ?? '_' }}</td>
                    </tr>
                    <tr>
                        <th>Lịch hẹn</th>
                        <td>{{ $booking->scheduled_start ?? '_' }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($booking->status instanceof BaseEnum)
                                {!! $booking->status->toHtml() !!}
                            @else
                                {{ $booking->status ?? '_' }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section-title">Chi tiết dịch vụ</div>

        @php
            $items = $booking->bookingServices ?? collect();
            $subtotal = 0;

            foreach ($items as $it) {
                $subtotal += (float) ($it->price ?? 0);
            }

            $discount = (float) ($booking->discount ?? 0);
            $total = max(0, $subtotal - $discount);
        @endphp

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 36%;">Dịch vụ</th>
                    <th style="width: 12%;" class="right nowrap">Thời lượng</th>
                    <th style="width: 18%;">Thợ phụ trách</th>
                    <th style="width: 14%;">Trạng thái</th>
                    <th style="width: 20%;" class="right nowrap">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $bookingService)
                    @php
                        $staffName = $bookingService->staff->user->name;
                        $staffText = $staffName ?: '#' . data_get($bookingService, 'assigned_staff_id', '_');
                    @endphp

                    <tr>
                        <td>
                            <b>{{ $bookingService->service_name ?? '_' }}</b>

                            @if (!empty($bookingService->note))
                                <div class="muted" style="margin-top:4px;">
                                    Ghi chú: {{ $bookingService->note }}
                                </div>
                            @endif

                            <div class="muted" style="margin-top:4px;">
                                Bắt đầu: {{ $bookingService->started_at ?? '_' }}<br>
                                Kết thúc: {{ $bookingService->finished_at ?? '_' }}
                            </div>
                        </td>

                        <td class="right nowrap">{{ (int) ($bookingService->duration ?? 0) }} phút</td>
                        <td>{{ $staffText }}</td>

                        <td>
                            @if ($bookingService->status instanceof BaseEnum)
                                {!! $bookingService->status->toHtml() !!}
                            @else
                                {{ $bookingService->status ?? '_' }}
                            @endif
                        </td>

                        <td class="right nowrap">
                            {{ number_format((float) ($bookingService->price ?? 0), 0, ',', '.') }} VND
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">Không có dịch vụ nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


        <table class="totals">
            <tr>
                <td class="label">Tạm tính</td>
                <td class="value nowrap">{{ number_format($subtotal, 0, ',', '.') }} VND</td>
            </tr>
            <tr>
                <td class="label">Giảm giá</td>
                <td class="value nowrap">{{ number_format($discount, 0, ',', '.') }} VND</td>
            </tr>
            <tr>
                <td class="label grand"><span style="font-weight: bold">Tổng thanh toán</span></td>
                <td class="value grand nowrap">{{ number_format($total, 0, ',', '.') }} VND</td>
            </tr>
        </table>

        <div class="footer">
            {{ config('app.brand_footer', 'Cảm ơn bạn đã sử dụng dịch vụ. Hẹn gặp lại!') }}
        </div>

    </div>
</body>

</html>

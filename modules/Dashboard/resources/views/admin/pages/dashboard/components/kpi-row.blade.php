<div class="row row-cols-lg-4 row-cols-md-2 row-cols-1">
    <div class="col mb-6">
        <div class="card h-100 dashboard-kpi-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-calendar-event'></i></span>
                    </div>
                </div>
                <p class="kpi-label mb-1">Pending Bookings (Week)</p>
                <h4 class="kpi-value mb-0">{{ $kpis['pendingBookings']['value'] }}</h4>
                @php
                $growth = $kpis['pendingBookings']['growth'];
                $color = $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'muted' ); $icon=$growth> 0 ? 'bx-up-arrow-alt' : ($growth < 0 ? 'bx-down-arrow-alt' : 'bx-minus' ); @endphp <small class="text-{{ $color }} fw-medium">
                        <i class='bx {{ $icon }}'></i> {{ number_format(abs($growth), 1) }}%
                        </small>
            </div>
        </div>
    </div>
    <div class="col mb-6">
        <div class="card h-100 dashboard-kpi-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-calendar-event'></i></span>
                    </div>
                </div>
                <p class="kpi-label mb-1">Bookings (Week)</p>
                <h4 class="kpi-value mb-0">{{ $kpis['bookingThisWeek']['value'] }}</h4>
                @php
                $growth = $kpis['bookingThisWeek']['growth'];
                $color = $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'muted' ); $icon=$growth> 0 ? 'bx-up-arrow-alt' : ($growth < 0 ? 'bx-down-arrow-alt' : 'bx-minus' ); @endphp <small class="text-{{ $color }} fw-medium">
                        <i class='bx {{ $icon }}'></i> {{ number_format(abs($growth), 1) }}%
                        </small>
            </div>
        </div>
    </div>
    <div class="col mb-6">
        <div class="card h-100 dashboard-kpi-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class='bx bx-money'></i></span>
                    </div>
                </div>
                <p class="kpi-label mb-1">Revenue (Week)</p>
                <h4 class="kpi-value mb-0">{{ number_format($kpis['revenueThisWeek']['value'], 0, ',', '.') }} <small class="text-muted">VND</small></h4>
                @php
                $growth = $kpis['revenueThisWeek']['growth'];
                $color = $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'muted' ); $icon=$growth> 0 ? 'bx-up-arrow-alt' : ($growth < 0 ? 'bx-down-arrow-alt' : 'bx-minus' ); @endphp <small class="text-{{ $color }} fw-medium">
                        <i class='bx {{ $icon }}'></i> {{ number_format(abs($growth), 1) }}%
                        </small>
            </div>
        </div>
    </div>

    <div class="col mb-6">
        <div class="card h-100 dashboard-kpi-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-trending-up'></i></span>
                    </div>
                </div>
                <p class="kpi-label mb-1">Expected Revenue (Week)</p>
                <h4 class="kpi-value mb-0">{{ number_format($kpis['expectedRevenueThisWeek']['value'], 0, ',', '.') }} <small class="text-muted">VND</small></h4>
                @php
                $growth = $kpis['expectedRevenueThisWeek']['growth'];
                $color = $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'muted' ); $icon=$growth> 0 ? 'bx-up-arrow-alt' : ($growth < 0 ? 'bx-down-arrow-alt' : 'bx-minus' ); @endphp <small class="text-{{ $color }} fw-medium">
                        <i class='bx {{ $icon }}'></i> {{ number_format(abs($growth), 1) }}%
                        </small>
            </div>
        </div>
    </div>
</div>

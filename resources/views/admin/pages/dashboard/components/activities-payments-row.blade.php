<div class="row">
    <div class="col-lg-4 mb-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Recent Activities</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">

                <div class="activity-timeline">
                    @forelse($recentActivities as $activity)
                        @php
                            $timeLine = match ($activity['status']) {
                                'in_progress' => 'pending',
                                'done' => 'completed',
                                'cancelled' => 'cancelled',
                                'confirmed' => 'confirmed',
                                default => 'pending',
                            };
                        @endphp
                        <div class="timeline-item timeline-{{ $timeLine }}">
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1 flex-wrap">
                                    <span class="timeline-title">{{ $activity['booking_code'] }}</span>
                                    <span
                                        class="badge bg-label-{{ $activity['status_color'] }}">{{ $activity['status_label'] }}</span>
                                </div>
                                <p class="timeline-text">{{ $activity['customer_name'] }}</p>
                                <div class="d-flex justify-content-between">
                                    <small class="timeline-time"><i
                                            class='bx bx-calendar me-1'></i>{{ $activity['scheduled_start'] }}</small>
                                    <small class="timeline-time">{{ $activity['created_at'] }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class='bx bx-calendar-x bx-lg mb-2'></i>
                            <p class="mb-0">No activities yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Statistics Chart -->
    <div class="col-lg-4 mb-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Payment Statistics</h5>
                @php
                    $labels = $paymentStats['labels'];
                    $series = $paymentStats['series'];
                    $icons = $paymentStats['icons'];
                @endphp
            </div>
            <div class="card-body">
                @foreach ($labels as $key => $label)
                    <div class="pt-4">
                        <ul class="p-0 m-0">
                            <li class="d-flex align-items-center mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets/img/icons/unicons/' . $icons[$key]) }}" alt="User"
                                        class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="d-block">Payment</small>
                                        <h6 class="fw-normal mb-0">{{ $label }}</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-2">
                                        <h6 class="fw-normal mb-0 badge bg-label-success">{{ $series[$key] }}</h6>
                                        <span class="text-muted"></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-6">
        <div class="card h-100 dashboard-chart-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Weekly Revenue</h5>
                <small class="text-muted">{{ $weekStart->format('d/m') }} - {{ $weekEnd->format('d/m/Y') }}</small>
            </div>
            <div class="card-body">
                <div id="revenueWeeklyChart" class="chart-container"></div>
            </div>
        </div>
    </div>
    <!-- Top Services & Categories Tabs -->
    <div class="col-lg-6 mb-6">
        <div class="card text-center h-100">
            <div class="card-header nav-align-top">
                <ul class="nav nav-pills flex-wrap row-gap-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-services" aria-controls="navs-pills-top-services"
                            aria-selected="true">Top Services</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-categories" aria-controls="navs-pills-top-categories"
                            aria-selected="false">Top Categories</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-countries" aria-controls="navs-pills-top-countries"
                            aria-selected="false">Top Countries</button>
                    </li>
                </ul>
            </div>

            @php
                $totalServiceCount = collect($topServices)->sum('count');
                $totalCategoryCount = collect($topCategories)->sum('count');
            @endphp

            <div class="tab-content pt-0 pb-4">
                <div class="tab-pane fade show active" id="navs-pills-top-services" role="tabpanel">
                    <div class="table-responsive text-start text-nowrap">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Service</th>
                                    <th>Qty</th>
                                    <th class="w-25">Data In Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topServices as $index => $service)
                                    @php
                                        $percentage =
                                            $totalServiceCount > 0 ? ($service['count'] / $totalServiceCount) * 100 : 0;
                                        $colors = ['success', 'primary', 'info', 'warning', 'danger'];
                                        $color = $colors[$index % count($colors)];
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <span
                                                        class="avatar-initial rounded-circle bg-label-{{ $color }}">{{ substr($service['service_name'], 0, 1) }}</span>
                                                </div>
                                                <span class="text-heading">{{ $service['service_name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-heading">{{ $service['count'] }}</td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center gap-4">
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="fw-medium">{{ number_format($percentage, 2) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-top-categories" role="tabpanel">
                    <div class="table-responsive text-start text-nowrap">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category</th>
                                    <th>Qty</th>
                                    <th class="w-50">Data In Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCategories as $index => $category)
                                    @php
                                        $percentage =
                                            $totalCategoryCount > 0
                                                ? ($category['count'] / $totalCategoryCount) * 100
                                                : 0;
                                        $colors = ['success', 'primary', 'info', 'warning', 'danger'];
                                        $color = $colors[$index % count($colors)];
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <span
                                                        class="avatar-initial rounded-circle bg-label-{{ $color }}">{{ substr($category['name'], 0, 1) }}</span>
                                                </div>
                                                <span class="text-heading">{{ $category['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-heading">{{ $category['count'] }}</td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center gap-4">
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="fw-medium">{{ number_format($percentage, 2) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-top-countries" role="tabpanel">
                    <div class="table-responsive text-start text-nowrap">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Country</th>
                                    <th>Visits</th>
                                    <th class="w-25">Data In Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCountries as $index => $country)
                                    @php
                                        $colors = ['success', 'primary', 'info', 'warning', 'danger'];
                                        $color = $colors[$index % count($colors)];
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://flagcdn.com/24x18/{{ $country['iso_code'] }}.png"
                                                    srcset="https://flagcdn.com/48x36/{{ $country['iso_code'] }}.png 2x"
                                                    width="24" height="18" alt="{{ $country['country'] }}"
                                                    class="me-2 rounded-1" style="object-fit: cover;">
                                                <span class="text-heading">{{ $country['country'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-heading">{{ $country['count'] }}</td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center gap-4">
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-{{ $color }}"
                                                        role="progressbar"
                                                        style="width: {{ $country['percentage'] }}%"
                                                        aria-valuenow="{{ $country['percentage'] }}"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="fw-medium">{{ $country['percentage'] }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

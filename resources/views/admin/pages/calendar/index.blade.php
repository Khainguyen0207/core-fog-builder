@extends('admin.layouts.contentLayout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/app-calendar.scss') }}" />
@endsection

@section('title', 'Calendar')

@section('page-script')
    @vite(['resources/views/admin/assets/js/calendar-booking.js'])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card app-calendar-wrapper">
                <div class="row g-0">
                    <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                        <div class="px-3 pt-2">
                            <input type="text" class="form-control date-picker-single d-none" id="calendarDate" />
                        </div>
                        <hr class="mb-6 mx-n4 mt-3">
                        <div class="px-6 pb-2">
                            <div>
                                <h5>Status</h5>
                            </div>

                            {{-- <div class="form-check form-check-secondary mb-5 ms-2">
                            <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked="">
                            <label class="form-check-label" for="selectAll">View All</label>
                        </div> --}}

                            <div class="d-flex flex-col flex-column gap-2">
                                <label class="form-check d-inline-flex align-items-center ps-0 ms-0 gap-2 m-0">
                                    <span class="badge bg-label-secondary">Pending Confirmation</span>
                                </label>

                                <label class="form-check d-inline-flex align-items-center ps-0 ms-0 gap-2 m-0">
                                    <span class="badge bg-label-info">Confirmed</span>
                                </label>

                                <label class="form-check d-inline-flex align-items-center ps-0 ms-0 gap-2 m-0">
                                    <span class="badge bg-label-warning">In Progress</span>
                                </label>

                                <label class="form-check d-inline-flex align-items-center ps-0 ms-0 gap-2 m-0">
                                    <span class="badge bg-label-success">Done</span>
                                </label>

                                <label class="form-check d-inline-flex align-items-center ps-0 ms-0 gap-2 m-0">
                                    <span class="badge bg-label-danger">Cancelled</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col app-calendar-content">
                        <div class="card shadow-none border-0">
                            <div class="card-body pb-0">
                                <div id="calendar" data-events-url="{{ route('admin.calendar.events') }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

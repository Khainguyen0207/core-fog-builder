/**
 * Calendar Booking Logic
 */

import { Calendar, formatDate } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import { formatIsoTimeString } from '@fullcalendar/core/internal';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const eventsUrl = calendarEl.dataset.eventsUrl || '/admin/calendar/events';

    let calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, bootstrap5Plugin],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        themeSystem: 'bootstrap5',
        titleFormat: { year: 'numeric', month: 'numeric', day: 'numeric' },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List Week'
        },
        dayMaxEvents: 3,
        moreLinkClick: "popover",
        buttonIcons: {
            prev: 'chevron-left bx bx-chevron-left',
            next: 'chevron-right bx bx-chevron-right',
            prevYear: 'chevrons-left bx bx-chevrons-left',
            nextYear: 'chevrons-right bx bx-chevrons-right'
        },
        slotDuration: '00:10:00',
        slotLabelInterval: '00:30:00',
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        timeZone: 'Asia/Ho_Chi_Minh',

        events: function (fetchInfo, successCallback, failureCallback) {
            toggleLoading(true);

            const params = new URLSearchParams({
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
            });

            const staffFilter = document.getElementById('staffFilter');
            if (staffFilter && staffFilter.value) {
                params.append('staff_id', staffFilter.value);
            }

            document.querySelectorAll('.input-filter:checked').forEach(cb => {
                params.append('filters[]', cb.dataset.value);
            });

            fetch(`${eventsUrl}?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {

                    const events = data.map(event => {
                        const color = `bg-label-${event.extendedProps.color}`;

                        return {
                            ...event,
                            classNames: event.classNames ? (Array.isArray(event.classNames) ? [...event.classNames, color] : [event.classNames, color]) : [color]
                        };
                    });

                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                    showErrorToast('Failed to load events');
                })
                .finally(() => {
                    toggleLoading(false);
                });
        },
        eventClick: function (info) {
            window.location.href = `/admin/bookings/${info.event._def.publicId}`;
        },
        eventContent: function (arg) {
            const name = arg.event._def.title ?? ""
            const booking_code = arg.event._def.extendedProps.booking_code ?? ""
            const start = arg.event.extendedProps.start;
            const end = arg.event.extendedProps.end;

            return {
                html: `
                    <div class="fc-event-title cursor-pointer">
                        ${start} to ${end} <br>
                        ${name} <br>
                        ${booking_code}
                    </div>
                `
            }
        },
        eventDidMount(info) {
            const start = info.event.extendedProps.start;
            const end = info.event.extendedProps.end;

            new bootstrap.Tooltip(info.el, {
                title: `
                    Time: ${start} to ${end}<br>
                    Customer : ${info.event.title}<br>
                    ID: ${info.event.extendedProps.booking_code}<br>
                    Click to view details
                `,
                html: true,
                placement: 'top'
            })
        }
    });

    calendar.render();

    const datePickerEl = document.querySelector('.date-picker-single');
    if (datePickerEl && datePickerEl._flatpickr) {
        datePickerEl._flatpickr.config.onChange.push(function (selectedDates, dateStr) {
            if (selectedDates.length > 0) {
                calendar.gotoDate(selectedDates[0]);
            }
        });
    } else if (datePickerEl) {
        datePickerEl.addEventListener('change', (e) => {
            if (e.target.value) calendar.gotoDate(e.target.value);
        });
    }

    const filters = document.querySelectorAll('.input-filter, #staffFilter');
    let debounceTimer;

    filters.forEach(filter => {
        filter.addEventListener('change', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                calendar.refetchEvents();
            }, 300);
        });
    });

    function toggleLoading(isLoading) {
        const wrapper = document.querySelector('.app-calendar-wrapper');
        if (wrapper) {
            if (isLoading) {
                wrapper.classList.add('is-loading');
            } else {
                wrapper.classList.remove('is-loading');
            }
        }
    }

    function showErrorToast(msg) {
        console.error(msg);
    }

    function formatTimeHMS(date) {
        if (!date) return ''

        return date.toLocaleTimeString('en-EN', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: 'Asia/Ho_Chi_Minh',
        })
    }
});

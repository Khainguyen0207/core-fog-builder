'use strict'

$(document).ready(function () {
    $('input.daterangepicker-single').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        }
    });

    $('input.daterangepicker-range').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        autoApply: true,
        locale: {
            format: 'HH:mm'
        },
        isInvalidDate: function (date) {
            return !date.isSame(moment(), 'day');
        }
    });
})
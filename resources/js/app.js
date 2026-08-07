
import '../views/admin/assets/vendor/libs/jquery/jquery.js';


const $ = window.jQuery = window.$ = window.jQuery || window.$;

if (!$ || !$.fn) {
    throw new Error('jQuery chưa attach vào window');
}

import '../views/admin/assets/vendor/js/bootstrap.js';
import '../views/admin/assets/vendor/js/libs.js';

import '../views/admin/assets/vendor/libs/bootstrap-select/bootstrap-select.js';

import 'select2/dist/js/select2.min.js';

import 'datatables.net-bs5';

import 'daterangepicker';

import PerfectScrollbar from 'perfect-scrollbar';
window.PerfectScrollbar = PerfectScrollbar;

import Swal from 'sweetalert2';
window.Swal = Swal;

import ApexCharts from 'apexcharts';
$.ApexCharts = ApexCharts;

import '../views/admin/assets/vendor/js/helpers.js';
import '../views/admin/assets/vendor/js/menu.js';
import '../views/admin/assets/js/main.js';
import '../views/admin/assets/js/app.js';
import '../views/admin/assets/js/config.js';
import '../views/admin/assets/js/dashboards-analytics.js';

$(function () {
    $('.selectpicker').selectpicker?.();
    $('.date-picker-single').flatpickr({
        inline: true,
        allowInput: false,
        monthSelectorType: "static"
    });
});

function initQuillOnce(selector, options) {
    const el = document.querySelector(selector);
    if (!el) return null;

    if (el.__quillInstance) return el.__quillInstance;

    el.__quillInstance = new Quill(el, options);
    return el.__quillInstance;
}


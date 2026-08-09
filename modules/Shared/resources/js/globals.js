import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import flatpickr from 'flatpickr';
import moment from 'moment';
import PerfectScrollbar from 'perfect-scrollbar';
import Quill from 'quill';
import Swal from 'sweetalert2';

window.$ = window.jQuery = $;
window.bootstrap = bootstrap;
window.flatpickr = flatpickr;
window.moment = moment;
window.PerfectScrollbar = PerfectScrollbar;
window.Quill = Quill;
window.Swal = Swal;

export { $, bootstrap, flatpickr, moment, PerfectScrollbar, Quill, Swal };

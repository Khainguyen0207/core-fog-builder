import './globals.js';
import { $ } from './globals.js';
import 'bootstrap-select';
import 'select2/dist/js/select2.full.js';
import 'datatables.net-bs5';
import 'daterangepicker';

if ($.fn.selectpicker?.Constructor) {
    $.fn.selectpicker.Constructor.BootstrapVersion = '5';
}

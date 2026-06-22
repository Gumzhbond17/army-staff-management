import 'bootstrap';
import $ from 'jquery';
import initSelect2 from 'select2';
import Swal from 'sweetalert2';
import flatpickr from 'flatpickr';

window.$ = window.jQuery = $;
window.Swal = Swal;
window.flatpickr = flatpickr;

// select2 exports a CJS factory; call it to attach $.fn.select2
initSelect2(window, $);

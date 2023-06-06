import _ from 'lodash';
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import Swal from 'sweetalert2';

import Popper from 'popper.js';

import jQuery from 'jquery';

import DataTable from 'datatables.net-bs4';

import 'bootstrap';
import 'admin-lte';


try {
  window.$ = window.jQuery = jQuery;
  window.Popper = Popper;
  window.Swal = Swal;

  DataTable(window, window.$);

  window._ = _;


  window.SwalWithBootstrap = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary mx-3',
      cancelButton: 'btn btn-danger mx-3',
    },
    buttonsStyling: false,
  });

} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';

try {
  window.axios = axios;

  window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
} catch (error) {
  
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

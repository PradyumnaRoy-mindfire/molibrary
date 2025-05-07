// import axios from 'axios';
// window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



// //for pusher notification
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';


// window.Pusher = Pusher;

// console.log(window.userInfo && window.userInfo.isAuthenticated,"first");
// if (window.userInfo && window.userInfo.isAuthenticated) {

//     window.Echo = new Echo({
//         broadcaster: 'pusher',
//         key: import.meta.env.VITE_PUSHER_APP_KEY,   
//         cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//         forceTLS: true,
//     });

//     console.log(window.Echo,"second");

//     // Only authorized members can see to this channel
//     window.Echo.private('library.channel')
//         .listen('.book.added', (e) => {
//             // Show notification
//             Swal.fire({
//                 title: 'New Book Added!',
//                 text: `"${e.book.title}" has been added to the library`,
//                 icon: 'success',
//                 toast: true,
//                 position: 'top-end',
//                 showConfirmButton: false,
//                 timer: 10000,
//                 timerProgressBar: true
//             });
//         });
// }


// import './echo';

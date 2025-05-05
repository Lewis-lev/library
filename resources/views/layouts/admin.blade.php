<style>
    /* Slide down from top when appearing */
    .toast.slide-top-in {
        animation: slideTopIn .5s cubic-bezier(0.42, 0, 0.58, 1);
    }

    @keyframes slideTopIn {
        from {
            opacity: 0;
            transform: translateY(-40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Slide up when disappearing */
    .toast.slide-top-out {
        animation: slideTopOut .5s cubic-bezier(0.42, 0, 0.58, 1) forwards;
    }

    @keyframes slideTopOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }


        to {
            opacity: 0;
            transform: translateY(-40px);
        }
    }
</style>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Library')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

</head>

<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
    @include('layouts.navigation')

    <div id="notification-toast-container"
        style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1055; min-width: 250px;">
        <!-- Toasts will be appended dynamically -->
    </div>

    <main class="py-10">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(auth()->check() && auth()->user()->role == 'admin')
            window.Echo = new Echo({
                broadcaster: "pusher",
                key: "{{ config('broadcasting.connections.pusher.key') }}",
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                forceTLS: true
            });

            window.Echo.channel("admin-channel")
                .listen(".BookBorrowed", function (e) {
                    let container = document.getElementById('notification-toast-container');
                    if (!container) return;

                    // Unique ID for each toast
                    let toastId = 'toast-' + Date.now();

                    // Message
                    let message = `<strong class="me-auto"><i class="fa-solid fa-book-open"></i> Request sent!</strong>
                                                        <div><b>${e.user.name}</b> just sent a request to borrow "<b>${e.book.title}</b>"</div>`;

                    // Build the toast element
                    let toastElem = document.createElement('div');
                    toastElem.className = 'toast show align-items-center text-bg-primary border-0 mb-2 shadow slide-top-in';
                    toastElem.id = toastId;
                    toastElem.setAttribute('role', 'alert');
                    toastElem.setAttribute('aria-live', 'assertive');
                    toastElem.setAttribute('aria-atomic', 'true');
                    toastElem.style.minWidth = '270px';
                    toastElem.innerHTML = `
                                            <div class="d-flex">
                                            <div class="toast-body">
                                                ${message}
                                            </div>
                                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>`;
                    container.appendChild(toastElem);

                    // Animation and remove handling
                    toastElem.addEventListener('animationend', function animIn(e) {
                        if (e.animationName === 'slideTopIn') {
                            toastElem.classList.remove('slide-top-in');
                            setTimeout(() => toastElem.classList.add('slide-top-out'), 3000);
                            toastElem.removeEventListener('animationend', animIn);
                        }
                    });
                    toastElem.addEventListener('animationend', function animOut(e) {
                        if (e.animationName === 'slideTopOut') {
                            toastElem.remove();
                            toastElem.removeEventListener('animationend', animOut);
                        }
                    });
                    toastElem.querySelector('.btn-close').onclick = function () {
                        toastElem.classList.add('slide-top-out');
                        toastElem.classList.remove('show');
                        setTimeout(() => toastElem.remove(), 350);
                    };

                    // ----- Badge: Real Time Quantity Update -----
                    // let badgeId = 'quantity-badge-' + (e.book.book_id ?? e.book.id);
                    // let badge = document.getElementById(badgeId);
                    // if (badge) {
                    //     badge.textContent = 'Stock: ' + (e.book.quantity ?? 'N/A');
                    //     badge.classList.add('bg-success');
                    //     setTimeout(() => badge.classList.remove('bg-success'), 300);
                    // }
                });
        @endif
    });
</script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            responsive: true,
        });
    });
</script>

</html>

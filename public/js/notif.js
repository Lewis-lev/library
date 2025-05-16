document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.pusherKey === 'undefined' || typeof window.pusherCluster === 'undefined') {
        // Don't initialize Echo if config is missing
        return;
    }
    window.Echo = new Echo({
        broadcaster: "pusher",
        key: window.pusherKey,
        cluster: window.pusherCluster,
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
});

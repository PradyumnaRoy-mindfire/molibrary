// side bar toggling
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const toggleBtn = document.getElementById("sidebarToggle");

if (localStorage.getItem("sidebar-collapsed") === "true") {
    sidebar.classList.add("collapsed");
    mainContent?.classList.add("full");
}

toggleBtn.addEventListener("click", () => {
    // sidebar.classList.toggle("collapsed");
    mainContent?.classList.toggle("full");

    const isCollapsed = sidebar.classList.contains("collapsed");
    localStorage.setItem("sidebar-collapsed", isCollapsed);
});


// highlight the active link based on the url
const currentUrl = window.location.href;

document.querySelectorAll('#sideNavLinks .nav-link').forEach(link => {
    const linkUrl = link.href;
    if (currentUrl === linkUrl || currentUrl.startsWith(linkUrl) || currentUrl.endsWith(linkUrl)) {
        link.classList.add('active');
    }

    link.addEventListener('click', () => {
        document.querySelectorAll('#sideNavLinks .nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
    });
});


            //notification handler code

// Toggle notification box
let notificationBtn = document.getElementById('notificationBtn');
let notificationBox = document.getElementById('notificationBox');
let notificationBackDrop = document.getElementsByClassName('modal-backdrop');
notificationBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    if (notificationBox.style.display === 'none' || notificationBox.style.display === '') {
        notificationBox.style.display = 'block';
    } else {
        notificationBox.style.display = 'none';
    }
});

// Close the  notification box when clicking outside
document.addEventListener('click', function (e) {
    if (!notificationBox.contains(e.target) && e.target !== notificationBtn) {
        if (notificationBackDrop.length < 1) {
            notificationBox.style.display = 'none';
        }
    }
});


document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {
        // Check if the clicked element has the custom attribute data read 
        const markAsReadId = e.target.getAttribute('data-mark-as-read');

        // If it has the attribute, handle it and prevent all other click events
        if (markAsReadId) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation(); // This is the key line that stops all other event handlers

            markAsRead(markAsReadId);

            // Return false to ensure no other handlers runs
            return false;
        }
    }, true);

         // Mark notification as read
    function markAsRead(id) {
        fetch(`/notifications/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Find notification item in DOM and remove unread class
                    const item = document.querySelector(`.notification-item[data-notification-id="${id}"]`);
                    if (item) {

                        item.classList.remove('unread');

                        item.style.display = 'none';
                    }
                    // Update the notification count
                    updateNotificationCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
    }

    // Mark all notifications as read handler code
    const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
    const notificationList = document.querySelector('#notificationList');
    const badge = document.getElementById('notificationBadge');
    markAllAsReadBtn.addEventListener('click', function () {
        markAllAsRead();
    });

    function markAllAsRead() {
        fetch(`/notifications/mark-all-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Find notification item in DOM and remove unread class
                   let items = document.querySelectorAll('.notification-item');
                   items.forEach(item => {
                       item.style.display = 'none';
                    
                   });
                   //remove the red bagdge from the bell
                   badge.style.display = 'none';
                   //remove the mark all as read button
                   markAllAsReadBtn.style.display = 'none';
                   //show the no notification message
                   notificationList.innerHTML = ' <li class="list-group-item text-center text-muted">No notifications</li>';
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
    }

    const notificationItems = document.querySelectorAll('.notification-item');

    // Setup Modal
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    const modalTitle = document.getElementById('notificationModalLabel');
    const modalBody = document.getElementById('notificationModalBody');
    const modalFooter = document.getElementById('notificationModalFooter');

    // to open the modal on click event to each notification ,show details
    notificationItems.forEach(item => {
        item.addEventListener('click', function () {

            const notificationId = this.getAttribute('data-notification-id');

            fetch(`/notifications/${notificationId}`)
                .then(response => response.json())
                .then(data => {
                    modalTitle.textContent = 'hello';
                    modalTitle.textContent = data.title;
                    modalBody.innerHTML = `<p>${data.message}</p> <small class="text-muted float-end">${data.time}</small>`;

                    if (data.action_url) {
                        //if there is already an anchor tag with id notificationActionLink
                        let actionLink = document.getElementById('notificationActionLink');
                        if (!actionLink) {
                            actionLink = document.createElement('a');
                            actionLink.id = 'notificationActionLink';
                            actionLink.className = 'btn btn-primary';
                            modalFooter.appendChild(actionLink);
                        }

                        actionLink.textContent = data.action_text || 'View Details';
                        actionLink.href = data.action_url;
                    }

                    // Show the modal
                    document.querySelector('.modal').style.zIndex = 1060;

                    notificationModal.show();


                })
                .catch(error => {
                    console.error('Error fetching notification details:', error);
                });
        });
    });

    // Update notification count badge
    function updateNotificationCount() {
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        // const badge = document.getElementById('notificationBadge');

        if (unreadItems.length > 0) {
            badge.textContent = unreadItems.length;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
});





                //showing sweetalerts and realtime notifications    

// logout
$(document).on("click", ".logout", function () {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Logout !",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = logoutUrl;
        }
    });
});

function showLoginToast() {
    let Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: 'success',
        title: 'Logged in successfully..'
    })
}


//showing notification when a book will be added
function bookAdded() {
    var channel = pusher.subscribe('library.channel');
    channel.bind('book.added', function (data) {
        let BookAddedToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 10000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        BookAddedToast.fire({
            icon: 'success',
            html: `New Book <strong>${data.book}</strong> just added now...`
        })

    });
}

function newBorrowRequest() {
    var channel = pusher.subscribe('borrow.channel');
    channel.bind('new.borrow.request', function (data) {
        let BookAddedToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 10000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        BookAddedToast.fire({
            icon: 'success',
            html: `New borrow request for <strong>${data.book}</strong> has just come...`,
        })

    });
}
function newReturnRequest() {
    var channel = pusher.subscribe('return.channel');
    channel.bind('new.return.request', function (data) {
        let BookAddedToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 10000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        BookAddedToast.fire({
            icon: 'success',
            html: `New return request for <strong>${data.book}</strong> has just come...`,
        })

    });
}

function newLibrarianRegistered() {
    var channel = pusher.subscribe('libraryadmin.channel');
    channel.bind('librarian.registered', function (data) {
        let BookAddedToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 10000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        BookAddedToast.fire({
            icon: 'success',
            html: `A new librarian <strong>${data.name}</strong> has just registered...`,
        })

    });
}
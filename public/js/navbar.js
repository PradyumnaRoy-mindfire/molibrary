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
    if (currentUrl === linkUrl || currentUrl.startsWith(linkUrl)||currentUrl.endsWith(linkUrl)) {
        link.classList.add('active');
    }

    link.addEventListener('click', () => {
        document.querySelectorAll('#sideNavLinks .nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
    });
});

// Notification toggle handling
// const notificationBtn = document.getElementById("notificationBtn");
// const notificationBox = document.getElementById("notificationBox");

// notificationBtn.addEventListener("click", () => {
//     notificationBox.style.display = notificationBox.style.display === "none" ? "block" : "none";
// });

// document.addEventListener("click", function(event) {
//     if (!notificationBtn.contains(event.target) && !notificationBox.contains(event.target)) {
//         notificationBox.style.display = "none";
//     }
// });

// logout
$(document).on("click", ".logout", function() {
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
    channel.bind('book.added', function(data) {
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
    channel.bind('new.borrow.request', function(data) {
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
    channel.bind('new.return.request', function(data) {
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
    channel.bind('librarian.registered', function(data) {
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
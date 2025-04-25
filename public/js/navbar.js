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
const notificationBtn = document.getElementById("notificationBtn");
const notificationBox = document.getElementById("notificationBox");

notificationBtn.addEventListener("click", () => {
    notificationBox.style.display = notificationBox.style.display === "none" ? "block" : "none";
});

document.addEventListener("click", function(event) {
    if (!notificationBtn.contains(event.target) && !notificationBox.contains(event.target)) {
        notificationBox.style.display = "none";
    }
});

// logout
$(document).on("click", ".logout", function() {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Logout !"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = logoutUrl;
        }
    });
});
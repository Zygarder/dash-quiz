// Sidebar toggle
const menuBtn = document.querySelector("#menuBtn");
const sidebar = document.querySelector("#sidebar");

menuBtn.addEventListener("click", (e) => {
  e.stopPropagation(); // Prevent closing sidebar immediately
  sidebar.classList.toggle("active");
});

// Close sidebar when clicking outside it
document.addEventListener("click", (e) => {
  if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
    sidebar.classList.remove("active");
  }
});
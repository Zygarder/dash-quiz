// Sidebar toggle
const menuBtn = document.getElementById("menuBtn");
const sidebar = document.getElementById("sidebar");

menuBtn.addEventListener("click", (e) => {
  e.stopPropagation(); // Prevent triggering the document click
  sidebar.classList.toggle("active");
});

// Close sidebar when clicking outside it
document.addEventListener("click", (e) => {
  const isClickInsideSidebar = sidebar.contains(e.target);
  const isClickOnMenu = menuBtn.contains(e.target);

  if (!isClickInsideSidebar && !isClickOnMenu) {
    sidebar.classList.remove("active");
  }
});

// === Search function :) ===
const searchInput = document.getElementById("searchInput");
const table = document.getElementById("recordsTable");
const rows = table.getElementsByTagName("tr");

function filterRecords() {
  const searchVal = searchInput.value.toLowerCase();

  for (let i = 1; i < rows.length; i++) {
    const topic = rows[i].cells[1].textContent.toLowerCase();
    const score = rows[i].cells[2].textContent.toLowerCase();

    const matchesSearch =
      topic.includes(searchVal) || score.includes(searchVal);

    rows[i].style.display = matchesSearch ? "" : "none";
  }
}

searchInput.addEventListener("input", filterRecords);
//dateFilter.addEventListener('input', filterRecords);

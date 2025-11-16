// === Search function ===
const searchInput = document.querySelector("#searchInput");
const table = document.querySelector("#recordsTable");
const rows = table ? table.querySelectorAll("tr") : [];

// Attach search event
if (searchInput) {
  searchInput.addEventListener("input", function filterRecords() {
    const searchVal = searchInput.value.toLowerCase();

    rows.forEach((row, index) => {
      if (index === 0) return; // Skip header row

      const cells = row.querySelectorAll("td");
      const topic = cells[1]?.textContent.toLowerCase() || "";
      const score = cells[2]?.textContent.toLowerCase() || "";

      row.style.display = topic.includes(searchVal) || score.includes(searchVal) ? "" : "none";
    });
  });
}

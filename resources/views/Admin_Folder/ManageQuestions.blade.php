<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin | Manage Quizzes</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <header class="admin-header">
    <h2>Dash Quiz Admin Dashboard</h2>
    <a href="index.html" class="logout-btn">Log Out</a>
  </header>

  <div class="admin-container">
    <aside class="admin-sidebar">
      <h3 class="sidebar-title">Admin Menu</h3>
      <nav>
        <ul>
          <li><a href="admindashboard.html">Dashboard</a></li>
          <li class="active"><a href="adminquizzes.html">Manage Quizzes</a></li>
          <li><a href="adminusers.html">Users Table</a></li>
          <li><a href="adminsettings.html">Settings</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-section">
        <h3 class="section-title">Manage Quizzes</h3>

        <button class="add-btn">+ Add New Quiz</button>

        <div class="admin-table">
          <div class="table-header">
            <span>Quiz ID</span>
            <span>Title</span>
            <span>Total Items</span>
            <span>Date Created</span>
            <span>Actions</span>
          </div>

          <!-- Placeholder Quizzes -->
          <div class="table-row">
            <span>Q001</span>
            <span>Certificate of Competency 1</span>
            <span>5</span>
            <span>2025-10-20</span>
            <span>
              <button class="action-btn edit">Edit</button>
              <button class="action-btn delete">Delete</button>
            </span>
          </div>

          <div class="table-row">
            <span>Q002</span>
            <span>Certificate of Competency 2</span>
            <span>5</span>
            <span>2025-10-22</span>
            <span>
              <button class="action-btn edit">Edit</button>
              <button class="action-btn delete">Delete</button>
            </span>
          </div>

          <div class="table-row">
            <span>Q003</span>
            <span>Certificate of Competency 3</span>
            <span>5</span>
            <span>2025-10-25</span>
            <span>
              <button class="action-btn edit">Edit</button>
              <button class="action-btn delete">Delete</button>
            </span>
          </div>
        </div>
      </section>
    </main>
  </div>

  <footer class="admin-footer">
    © 2025 Dash Quiz | All Rights Reserved.
  </footer>
</body>
</html>

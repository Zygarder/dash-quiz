<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin | Users Table</title>
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
          <li><a href="adminquizzes.html">Manage Quizzes</a></li>
          <li class="active"><a href="adminusers.html">Users Table</a></li>
          <li><a href="adminsettings.html">Settings</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-section">
        <h3 class="section-title">Registered Users</h3>

        <div class="admin-table">
          <div class="table-header">
            <span>User ID</span>
            <span>Name</span>
            <span>Email</span>
            <span>Role</span>
            <span>Date Registered</span>
          </div>

          <div class="table-row">
            <span>001</span>
            <span>John Doe</span>
            <span>johndoe@email.com</span>
            <span>Student</span>
            <span>10/28/2025</span>
          </div>

          <div class="table-row">
            <span>002</span>
            <span>Jane Smith</span>
            <span>janesmith@email.com</span>
            <span>Student</span>
            <span>10/29/2025</span>
          </div>

          <div class="table-row">
            <span>003</span>
            <span>Michael Cruz</span>
            <span>mcruz@email.com</span>
            <span>Admin</span>
            <span>10/30/2025</span>
          </div>
        </div>
      </section>
    </main>
  </div>

</body>
</html>

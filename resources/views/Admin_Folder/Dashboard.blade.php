<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin Dashboard</title>
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
          <li class="active"><a href="{{route('admin-dashboard')}}">Dashboard</a></li>
          <li><a href="{{route('')}}">Manage Quizzes</a></li>
          <li><a href="adminusers.html">Users Table</a></li>
          <li><a href="adminsettings.html">Settings</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-stats">
        <div class="admin-card">Total Registered</div>
        <div class="admin-card">Total Quizzes</div>
        <div class="admin-card">Active Users</div>
      </section>

      <section class="admin-details">
        <div class="admin-card wide">Total Quizzes</div>
        <div class="admin-card wide">Total Quizzes</div>
      </section>
    </main>
  </div>
  <footer class="admin-footer">
    © 2025 Dash Quiz | All Rights Reserved.
  </footer>

  <script>
    
  </script>
</body>
</html>

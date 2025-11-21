<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin | Settings</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
  <header class="admin-header">
    <h2>Dash Quiz Admin Dashboard</h2>
    <a href="{{ route('logout') }}" class="logout-btn">Log Out</a>
  </header>

  <div class="admin-container">
    <aside class="admin-sidebar">
      <h3 class="sidebar-title">Admin Menu</h3>
      <nav>
        <ul>
          <li><a href="{{ route('admin-board') }}">Dashboard</a></li>
          <li><a href="{{ route('quiz-manage') }}">Manage Quizzes</a></li>
          <li><a href="{{ route('user-table') }}">Users Table</a></li>
          <li class="active"><a href="{{ route('settings') }}">Settings</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-section">
        <h3 class="section-title">Settings</h3>

        <div class="settings-container">
          <!-- Profile Settings -->
          <div class="settings-card">
            <h4>Profile Settings</h4>
            <form class="settings-form">
              <label for="adminName">Admin Name</label>
              <input type="text" id="adminName" placeholder="Enter admin name" value="Administrator">

              <label for="adminEmail">Email</label>
              <input type="email" id="adminEmail" placeholder="Enter email" value="admin@dashquiz.com">

              <label for="adminPassword">Change Password</label>
              <input type="password" id="adminPassword" placeholder="Enter new password">
            </form>
          </div>

          <button class="save-btn">Save Changes</button>
        </div>
      </section>
    </main>
  </div>

  <footer class="admin-footer">
    © 2025 Dash Quiz | All Rights Reserved.
  </footer>
</body>
</html>

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
    <a href="{{ route('adminlogout') }}" class="logout-btn">Log Out</a>
  </header>

  <div class="admin-container">
    <aside class="admin-sidebar">
      <h3 class="sidebar-title">Admin Menu</h3>
      <nav>
        <ul>
          <li class="active"><a href="{{ route('admin-board') }}">Dashboard</a></li>
          <li><a href="{{ route('quiz-manage') }}">Manage Quizzes</a></li>
          <li><a href="{{ route('user-table') }}">Users Table</a></li>
          <li><a href="{{ route('srecords') }}">Student Records</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-stats">
        <div class="admin-card">Total Registered: {{ $dboard }}</div>
        <div class="admin-card">Total Quizzes: {{ $totalQuizzes }}</div>
        <div class="admin-card">Active Users</div>
      </section>

      <section class="admin-details">
        <div class="admin-card wide">Total Quizzes</div>
        <div class="admin-card wide">Total Quizzes</div>
      </section>
    </main>
  </div>
</body>
</html>

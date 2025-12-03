<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin | Users Table</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
          <li><a href="{{ route('admin-board') }}">Dashboard</a></li>
          <li><a href="{{ route('quiz-manage') }}">Manage Quizzes</a></li>
          <li><a href="{{ route('user-table') }}">Users Table</a></li>
          <li class="active"><a href="{{ route('srecords') }}">Student Records</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-section">

        <h3 class="section-title">Dasher Records</h3>

        <div class="records-table">
          <div class="records-table records-table-header">
            <span>Record ID</span>
            <span>User ID</span>
            <span>Quiz Title</span>
            <span>Score</span>
            <span>Date Completed</span>
          </div>

          @foreach ($quiz_records as $rec)
            <div class="records-table records-table-row">
              <span>{{ $rec->id }}</span>
              <span>{{ $rec->user_id }}</span>
              <span>{{ $rec->quiz->title }}</span>
              <span>{{ $rec->score }}</span>
              <span>{{ $rec->completed_at }}</span>
            </div>
          @endforeach
        </div>
      </section>
    </main>
  </div>

</body>

</html>
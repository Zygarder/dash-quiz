<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin | Manage Quizzes</title>
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
          <li class="active"><a href="{{ route('quiz-manage')}}">Manage Quizzes</a></li>
          <li><a href="{{ route('user-table') }}">Users Table</a></li>
          <li><a href="{{ route('srecords') }}">Student Records</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-section">

        @if (session('success'))
          <div style="padding:10px; background:lightgreen; margin-bottom:10px; border:1px solid green;">
            {{ session('success') }}
          </div>
        @endif

        <h3 class="section-title">Manage Quizzes</h3>

        <button onclick="window.location='{{ route('quiz-add') }}'" class="add-btn">+ Add New Quiz</button>

        <div class="quiz-table quiz-admin-table">
          <div class="quiz-table quiz-table-header">
            <span>Quiz ID</span>
            <span>Title</span>
            <span>Description</span>
            <span>Actions</span>
          </div>

          @foreach ($quizzes as $quiz)
            <div class="quiz-table-row">
              <span>
                <tr>{{ $quiz->id }}</tr>
              </span>
              <span>
                <tr>{{ $quiz->title }}</tr>
              </span>
              <span>
                <tr>{{ $quiz->description }}</tr>
              </span>
              <span>
                <button onclick="location.href='{{ route('quiz-edit', $quiz->id) }}'"
                  class="action-btn edit">Edit</button>
                <form action="{{ route('quizdel', $quiz->id) }}" method="POST" style="display: inline">
                  @csrf
                  @method('DELETE')
                  <button class="action-btn delete" onclick="return confirm('Are you sure you want to delete quiz {{ $quiz->title }}')">Delete</button>
                </form>

              </span>
            </div>
          @endforeach
        </div>
      </section>
    </main>
  </div>
</body>

</html>
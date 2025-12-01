<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dash Quiz | User Profile</title>
</head>
<link rel="stylesheet" href="{{ asset('css/profile-page.css') }}" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  .alert-side {
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 999;
    border-radius: 10px;
    background-color: pink;
    width: 300px;
    padding: 10px;
    display: none;
    /* hidden by default */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  }

  .alert-side.active {
    display: block;
  }

  .alert-close {
    position: absolute;
    top: 5px;
    right: 10px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
  }

  .alert-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
  }

  .alert-body div {
    line-height: 16px;
    font-size: 12px;
    margin-bottom: 5px;
  }

  .picture-set {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background-color: #f9f9f9;
    text-align: center;
    font-family: sans-serif;
  }

  .file-label {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
  }

  .file-label:hover {
    background-color: #0056b3;
  }

  .file-input {
    display: none;
    /* hide the default file input */
  }

  .upload-btn {
    display: inline-block;
    padding: 10px 25px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .upload-btn:hover {
    background-color: #1e7e34;
  }
</style>

<body>

  <!-- Sidebar -->
  @include('User_Folder.Sidebar')

  <header class="top-bar">
    <button class="menu-btn" id="menuBtn">&#9776;</button>
    <h2>My Profile</h2>
    <a href="{{route('logout-user')}}" class="logout-btn">Log Out</a>
  </header>

  <!-- Main Content -->
  <main class="main-content" id="mainContent">
    <div class="profile-card">

      <div class="profile-header">
        <div class="avatar">😎</div>

        <div class="picture-set">
          <form action="/upload" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="file-label">
              Choose File
              <input type="file" name="myfile" class="file-input">
            </label>
            <button type="submit" class="upload-btn">Upload</button>
          </form>
        </div>

        <h2 id="username">{{$dasher->first_name}}</h2>
        <small id="userEmail">{{$dasher->email}}</small>
      </div>

      <div class="profile-details">
        <div class="detail-row">
          <span>Full Name:</span>
          <b id="fullName">{{$fullname}}</b>
        </div>

        <div class="detail-row">
          <span>Date Joined:</span>
          <b id="dateJoined">{{$dasher->created_at->format('M-d-Y')}}</b>
        </div>

        <div class="detail-row">
          <span>Quizzes Taken:</span>
          <b id="quizzesTaken">{{ $countUsers }}</b>
        </div>
      </div>

      <div class="profile-buttons">
        <button class="btn-primary" id="editProfileBtn">Edit Profile</button>
        <button class="btn-secondary" id="changePassBtn">Change Password</button>
      </div>
    </div>
  </main>

  <div class="alert-side" id="alertSide">
    <div class="alert-close" id="alertClose">&times;</div>
    <div class="alert-body">
      <!-- Error log -->
      @if ($errors->any())
        @foreach ($errors->all() as $error)
          <div style="color: red;">{{ $error }}</div>
        @endforeach
      @endif

      @if (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
      @endif

      @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
      @endif
    </div>
  </div>



  <!-- ======= MODALS ======= -->
  <!-- Edit Profile Modal -->
  <div class="modal" id="editProfileModal">
    <div class="modal-content">
      <h3>Edit Profile</h3>
      <form action="{{ route('profile-update') }}" method="POST">
        @csrf
        @method('PUT')
        <label>Full Name</label>
        <input type="text" id="editFullName" name="fullName"
          value="{{$dasher->first_name . ' ' . $dasher->last_name}}" />
        <label>Email</label>
        <input type="email" id="editEmail" name="email" value="{{ $dasher->email }}" disabled readonly />
        <div class="modal-buttons">
          <button type="button" class="cancel-btn" id="cancelEdit">Cancel</button>
          <button type="submit" class="save-btn" id="saveEdit">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Change Password Modal -->
  <!-- Change Password Modal -->
  <div class="modal" id="changePassModal">
    <div class="modal-content">
      <h3>Change Password</h3>
      <form action="{{ route('profile-new-password') }}" method="POST">
        @csrf
        @method('PUT')



        <label for="currentPass">Current Password</label>
        <input type="password" id="currentPass" name="current_password" />

        <label for="newPass">New Password</label>
        <input type="password" id="newPass" name="new_password" />

        <label for="confirmPass">Confirm New Password</label>
        <input type="password" id="confirmPass" name="new_confirm_password" />

        <div class="modal-buttons">
          <button type="button" class="cancel-btn" id="cancelPass">Cancel</button>
          <button type="submit" class="save-btn" id="savePass">Save</button>
        </div>
      </form>
    </div>
  </div>


  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
  <script>

    // === Edit Profile Modal ===
    const editProfileBtn = document.getElementById("editProfileBtn");
    const editProfileModal = document.getElementById("editProfileModal");
    const cancelEdit = document.getElementById("cancelEdit");
    const saveEdit = document.getElementById("saveEdit");

    editProfileBtn.addEventListener("click", () => {
      editProfileModal.style.display = "flex";
    });

    cancelEdit.addEventListener(
      "click",
      () => (editProfileModal.style.display = "none")
    );

    saveEdit.addEventListener("click", () => {
      editProfileModal.style.display = "none";
    });

    // === Change Password Modal ===
    const changePassBtn = document.getElementById("changePassBtn");
    const changePassModal = document.getElementById("changePassModal");
    const cancelPass = document.getElementById("cancelPass");
    const savePass = document.getElementById("savePass");

    changePassBtn.addEventListener("click", () => {
      changePassModal.style.display = "flex";
    });

    cancelPass.addEventListener("click", () => (changePassModal.style.display = "none"));

    savePass.addEventListener("click", () => {
      const currentPass = document.getElementById("currentPass").value;
      const newPass = document.getElementById("newPass").value;
      const confirmPass = document.getElementById("confirmPass").value;

      changePassModal.style.display = "none";
      alert("Password changed successfully!");
    });


    //for  float alert errors
    const alertSide = document.getElementById('alertSide');
    const alertClose = document.getElementById('alertClose');

    // Show alert if it has content
    if (alertSide.querySelector('.alert-body').children.length > 0) {
      alertSide.classList.add('active');

      // Auto-hide after 5 seconds
      setTimeout(() => {
        alertSide.classList.remove('active');
      }, 5000);
    }

    // Close button
    alertClose.addEventListener('click', () => {
      alertSide.classList.remove('active');
    });

  </script>
</body>

</html>
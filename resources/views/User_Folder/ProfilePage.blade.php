<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dash Quiz | User Profile</title>
</head>
  <link rel="stylesheet" href="{{ asset('css/profile-page.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
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

  <!-- ======= MODALS ======= -->
  <!-- Edit Profile Modal -->
  <div class="modal" id="editProfileModal">
    <div class="modal-content">
      <h3>Edit Profile</h3>
      <label>Full Name</label>
      <input type="text" id="editFullName" />
      <label>Email</label>
      <input type="email" id="editEmail" />
      <label>Role</label>
      <input type="text" id="editRole" />

      <div class="modal-buttons">
        <button class="cancel-btn" id="cancelEdit">Cancel</button>
        <button class="save-btn" id="saveEdit">Save</button>
      </div>
    </div>
  </div>

  <!-- Change Password Modal -->
  <div class="modal" id="changePassModal">
    <div class="modal-content">
      <h3>Change Password</h3>
      <label>Current Password</label>
      <input type="password" id="currentPass" />
      <label>New Password</label>
      <input type="password" id="newPass" />
      <label>Confirm New Password</label>
      <input type="password" id="confirmPass" />

      <div class="modal-buttons">
        <button class="cancel-btn" id="cancelPass">Cancel</button>
        <button class="save-btn" id="savePass">Save</button>
      </div>
    </div>
  </div>

  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
  <script>

    // === PROFILE DATA MODALS ===
    let profile = {
      username: "Username",
      email: "user@example.com",
      fullName: "John Doe",
      role: "Student",
      dateJoined: "October 10, 2025",
      quizzesTaken: 18,
      password: "12345",
    };

    // === DOM Elements ===
    const usernameEl = document.getElementById("username");
    const userEmailEl = document.getElementById("userEmail");
    const fullNameEl = document.getElementById("fullName");
    const userRoleEl = document.getElementById("userRole");

    // === Edit Profile Modal ===
    const editProfileBtn = document.getElementById("editProfileBtn");
    const editProfileModal = document.getElementById("editProfileModal");
    const editFullName = document.getElementById("editFullName");
    const editEmail = document.getElementById("editEmail");
    const editRole = document.getElementById("editRole");
    const cancelEdit = document.getElementById("cancelEdit");
    const saveEdit = document.getElementById("saveEdit");

    editProfileBtn.addEventListener("click", () => {
      editFullName.value = profile.fullName;
      editEmail.value = profile.email;
      editRole.value = profile.role;
      editProfileModal.style.display = "flex";
    });

    cancelEdit.addEventListener(
      "click",
      () => (editProfileModal.style.display = "none")
    );

    saveEdit.addEventListener("click", () => {
      profile.fullName = editFullName.value;
      profile.email = editEmail.value;
      profile.role = editRole.value;

      fullNameEl.textContent = profile.fullName;
      userEmailEl.textContent = profile.email;
      userRoleEl.textContent = profile.role;

      editProfileModal.style.display = "none";
      alert("Profile updated successfully!");
    });

    // === Change Password Modal ===
    const changePassBtn = document.getElementById("changePassBtn");
    const changePassModal = document.getElementById("changePassModal");
    const cancelPass = document.getElementById("cancelPass");
    const savePass = document.getElementById("savePass");

    changePassBtn.addEventListener("click", () => {
      changePassModal.style.display = "flex";
    });

    cancelPass.addEventListener(
      "click",
      () => (changePassModal.style.display = "none")
    );

    savePass.addEventListener("click", () => {
      const currentPass = document.getElementById("currentPass").value;
      const newPass = document.getElementById("newPass").value;
      const confirmPass = document.getElementById("confirmPass").value;

      if (currentPass !== profile.password) {
        alert("Incorrect current password!");
        return;
      }

      if (newPass !== confirmPass) {
        alert("New passwords do not match!");
        return;
      }

      profile.password = newPass;
      changePassModal.style.display = "none";
      alert("Password changed successfully!");
    });

    // === Close modal when clicking outside ===
    window.addEventListener("click", (e) => {
      if (e.target === editProfileModal)
        editProfileModal.style.display = "none";
      if (e.target === changePassModal)
        changePassModal.style.display = "none";
    });
  </script>
</body>

</html>
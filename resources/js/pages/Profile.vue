<template>
  <div class="profile-page-wrapper">
    <header class="top-bar">
      <button class="menu-btn" id="menuBtn" @click="toggleSidebar">&#9776;</button>
      <h2>My Profile</h2>
      <a href="/logout-user" class="logout-btn">Log Out</a>
    </header>

    <main class="main-content" id="mainContent">
      <div class="profile-card">
        <div class="profile-header">
          <div class="avatar">
            <img :src="profileImageUrl" alt="DP" readonly draggable="false" />
          </div>
          <h2 id="username">{{ user.first_name }}</h2>
          <small id="userEmail">{{ user.email }}</small>

          <form @submit.prevent="handleUpload" class="picture-set" enctype="multipart/form-data">
            <label class="file-label">
              Change Profile
              <input type="file" @change="onFileChange" class="file-input">
            </label>
            <button type="submit" class="upload-btn" :disabled="loading">
              {{ loading ? 'Uploading...' : 'Upload' }}
            </button>
          </form>
        </div>

        <div class="profile-details">
          <div class="detail-row">
            <span>Full Name:</span>
            <b id="fullName">{{ fullName }}</b>
          </div>
          <div class="detail-row">
            <span>Date Joined:</span>
            <b id="dateJoined">{{ formattedDate }}</b>
          </div>
          <div class="detail-row">
            <span>Quizzes Taken:</span>
            <b id="quizzesTaken">{{ quizzesCount || 'N/A' }}</b>
          </div>
        </div>

        <div class="profile-buttons">
          <button @click="showEditModal = true">Edit Profile</button>
          <button @click="showDeleteModal = true" id="delete">Delete Account</button>
        </div>
      </div>
    </main>

    <transition name="fade">
      <div v-if="errorMessage" class="alert-error alert-side active">
        <div class="alert-close" @click="errorMessage = ''">&times;</div>
        <div class="alert-body">
          <div style="color: red;">{{ errorMessage }}</div>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div v-if="successMessage" class="alert-success alert-side active">
        <div class="alert-close" @click="successMessage = ''">&times;</div>
        <div class="alert-body">
          <div style="color: white;">{{ successMessage }}</div>
        </div>
      </div>
    </transition>

    <div v-if="showEditModal" class="modal" style="display: flex;">
      <div class="modal-content">
        <h3>Edit Profile</h3>
        <form @submit.prevent="updateProfile">
          <label>Full Name</label>
          <input type="text" v-model="editForm.fullName" />

          <label>Email</label>
          <input type="email" v-model="user.email" disabled />

          <label>Current Password</label>
          <input type="password" v-model="editForm.current_password" />

          <label>New Password</label>
          <input type="password" v-model="editForm.new_password" />

          <label>Confirm New Password</label>
          <input type="password" v-model="editForm.new_confirm_password" />

          <div class="modal-buttons">
            <button type="button" class="cancel-btn" @click="showEditModal = false">Cancel</button>
            <button type="submit" class="save-btn">Save</button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="showDeleteModal" class="modal" style="display: flex;">
      <div class="modal-content">
        <h3 style="text-align:center">Delete Account</h3>
        <p class="small-text">Are you sure you wanna delete your account?</p>
        <div class="modal-buttons">
          <button type="button" class="cancel-btn" @click="showDeleteModal = false">Cancel</button>
          <button type="button" class="save-btn" @click="deleteAccount">Yes, of course</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
// Import your CSS from resources
import '../css/style.css';
import '../css/profile-page.css';

// Props passed from Blade (passed via data attributes or global window object)
const props = defineProps(['initialData']);

// State
const user = ref(props.initialData.dasher);
const quizzesCount = ref(props.initialData.quizzesCount);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const loading = ref(false);
const selectedFile = ref(null);

const editForm = ref({
  fullName: `${user.value.first_name} ${user.value.last_name}`,
  current_password: '',
  new_password: '',
  new_confirm_password: ''
});

const fullName = computed(() => `${user.value.first_name} ${user.value.last_name}`);

const profileImageUrl = computed(() => {
  return user.value.profile_photo
    ? `/storage/images/profiles/${user.value.profile_photo}`
    : '/images/profiles/person.jpg';
});
const formattedDate = computed(() => {
  const d = new Date(user.value.created_at);
  return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
});

// Methods
const onFileChange = (e) => {
  selectedFile.value = e.target.files[0];
};

const handleUpload = async () => {
  if (!selectedFile.value) return;
  loading.value = true;
  const formData = new FormData();
  formData.append('myfile', selectedFile.value);

  try {
    const response = await fetch('/api/profile/upload', {
      method: 'POST',
      body: formData,
      headers: { 'Accept': 'application/json' }
    });
    const data = await response.json();
    if (response.ok) {
      successMessage.value = "Profile updated!";
      user.value.profile_photo = data.new_photo; // Adjust based on your API response
    }
  } catch (err) {
    errorMessage.value = "Upload failed.";
  } finally {
    loading.value = false;
    triggerAlertTimeout();
  }
};

const triggerAlertTimeout = () => {
  setTimeout(() => {
    successMessage.value = '';
    errorMessage.value = '';
  }, 5000);
};

const toggleSidebar = () => {
  document.body.classList.toggle('sidebar-active');
};
</script>

<style scoped>
.alert-error,
.alert-success {
  position: fixed;
  top: 10px;
  right: 10px;
  z-index: 999;
  border-radius: 10px;
  width: 300px;
  padding: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.alert-error {
  background-color: pink;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
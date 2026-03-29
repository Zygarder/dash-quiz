<template>
  <div class="main-content">
    <div class="profile-card" v-if="user">
      <div class="profile-header">
        <div :class="['avatar', { 'uploading': loading }]">
          <img :src="preview || profileImageUrl" draggable="false" />
          <div v-if="loading" class="avatar-loader"></div>
        </div>

        <h2>{{ user.first_name }}</h2>
        <small>{{ user.email }}</small>

        <label class="file-label">
          Change Photo
          <input type="file" @change="onFileChange" accept="image/*">
        </label>
      </div>

      <div class="profile-details">
        <div class="detail-row">
          <span class="label">Full Name</span>
          <span class="value">{{ userFullName }}</span>
        </div>

        <div class="detail-row">
          <span class="label">Date Joined</span>
          <span class="value">{{ formattedDate }}</span>
        </div>

        <div class="detail-row">
          <span class="label">Quizzes Taken</span>
          <span class="value">{{ quizzesCount || 'N/A' }}</span>
        </div>
      </div>

      <div class="profile-buttons">
        <button id="edit-profile" @click="showEditModal = true">
          Edit Profile
        </button>
        <button id="delete" class="danger" @click="showDeleteModal = true">
          Delete Account
        </button>
      </div>
    </div>
  </div>

  <ToastNotification :message="notification.message" :type="notification.type" @clear="notification.message = ''" />
  <EditProfileModal :show="showEditModal" :loading="loading" :user="user" @notify="handleNotify" @save="updateProfile"
    @close="showEditModal = false" />
  <DeleteAccountModal :show="showDeleteModal" @close="showDeleteModal = false" @deleted="handleDeleted" />
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"
import DeleteAccountModal from "@/components/ProfileModals/DeleteModal.vue"
import EditProfileModal from "@/components/ProfileModals/EditModal.vue"
import ToastNotification from "@/components/ToastNotification.vue"

import { useUser } from "@/composables/useUser"

const router = useRouter()

const { userFullName, fetchUser, userAvatar, user } = useUser()
const notification = ref({
  message: '',
  type: 'success'
})

const handleNotify = (payload) => {
  showToast(payload.message, payload.type)
}

const showEditModal = ref(false)
const showDeleteModal = ref(false)

const preview = ref(null)
const selectedFile = ref(null)
const loading = ref(false)

/*
|--------------------------------------------------------------------------
| Handle after deletion
|--------------------------------------------------------------------------
*/
const handleDeleted = () => {
  showDeleteModal.value = false
  router.push("/")
}
/*
|--------------------------------------------------------------------------
| Profile Computed
|--------------------------------------------------------------------------
*/
const profileImageUrl = computed(() => userAvatar.value)

const quizzesCount = computed(() => {
  return user.value?.quizzes_taken || 0
})

const showToast = (msg, type = 'success') => {
  notification.value.message = msg
  notification.value.type = type

  // Auto hide after 4 seconds
  setTimeout(() => {
    notification.message = ''
  }, 2000)
}

const formattedDate = computed(() => {
  if (!user.value?.created_at) return ""

  return new Date(user.value.created_at).toLocaleDateString()
})

/*
|--------------------------------------------------------------------------
| Update Profile
|--------------------------------------------------------------------------
*/
const updateProfile = async (formData) => {
  try {
    loading.value = true
    // Get CSRF cookie (Laravel Sanctum)
    await axios.get("/sanctum/csrf-cookie")
    const { data } = await axios.put("/api/profile/update", formData)

    // Update local user state
    if (data.data && user.value) {
      user.value.first_name = data.data.first_name
      user.value.last_name = data.data.last_name
      user.value.email = data.data.email
    }
    showEditModal.value = false
    showToast("Profile updated successfully!", "success")

  } catch (err) {
    const errorMsg = err.response?.data?.message || "Update failed"
    showToast(errorMsg, "error")
  } finally {
    loading.value = false
  }
}/*
|--------------------------------------------------------------------------
| Upload Photo Logic
|--------------------------------------------------------------------------
*/
const onFileChange = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  // 1. Basic Client-side Validation
  if (!file.type.startsWith('image/')) {
    showToast("Please select a valid image file.", "error")
    return
  }

  // 2. Set Local Preview immediately for better UX
  selectedFile.value = file
  preview.value = URL.createObjectURL(file)

  loading.value = true

  try {
    const formData = new FormData()
    formData.append("photo", file)

    // Ensure CSRF is handled for the POST request
    await axios.get("/sanctum/csrf-cookie")

    const { data } = await axios.post("/api/profile/photo", formData, {
      headers: { "Content-Type": "multipart/form-data" }
    })

    // 3. Update the user object with the new URL from the server
    if (data.photo_url && user.value) {
      user.value.profile_photo = data.new_photo
      showToast("Profile picture updated!", "success")
    }

  } catch (error) {
    console.error("Upload Error:", error)
    // Clear preview on failure so it doesn't look like it worked
    preview.value = null

    const errorMsg = error.response?.data?.message || "Failed to upload photo."
    showToast(errorMsg, "error")
  } finally {
    loading.value = false
  }
}

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/
// after updating user
onMounted(async () => {
  await fetchUser(true)
})

</script>

<style scoped>
/* MAIN CONTENT WRAPPER */
.main-content {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  /* Adjust based on your layout */
  padding: 20px;
}

/* PROFILE CARD */
.profile-card {
  width: 100%;
  max-width: 450px;
  /* Limits width on desktop */
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  padding: 30px;
  display: flex;
  flex-direction: column;
  animation: fadeIn 0.4s ease-out;
}

/* HEADER SECTION */
.profile-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  margin-bottom: 25px;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  border: 4px solid #f0edff;
  margin-bottom: 15px;
  box-shadow: 0 4px 10px rgba(75, 50, 168, 0.1);
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-header h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #1a1a1a;
}

.profile-header small {
  color: #6b7280;
  font-size: 0.9rem;
  margin-top: 4px;
}

.file-label {
  margin-top: 15px;
  font-size: 0.8rem;
  font-weight: 700;
  color: #4b32a8;
  cursor: pointer;
  padding: 8px 16px;
  border: 1.5px solid #4b32a8;
  border-radius: 50px;
  transition: all 0.2s;
}

.file-label:hover {
  background: #4b32a8;
  color: #fff;
}

.file-label input {
  display: none;
}

/* DETAILS SECTION */
.profile-details {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 18px;
  background: #f9faff;
  border-radius: 12px;
  border: 1px solid #f0edff;
}

.detail-row .label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #8e8eb2;
}

.detail-row .value {
  font-weight: 700;
  color: #1a1a1a;
  font-size: 0.95rem;
}

/* BUTTONS SECTION */
.profile-buttons {
  margin-top: 30px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

button {
  padding: 12px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.9rem;
  cursor: pointer;
  border: none;
  transition: transform 0.1s, opacity 0.2s;
}

button:active {
  transform: scale(0.97);
}

#edit-profile {
  background: #4b32a8;
  color: white;
}

#delete {
  background: #fff;
  color: #e53935;
  border: 1px solid #fee2e2;
}

#delete:hover {
  background: #fff5f5;
}

/* ANIMATION */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* RESPONSIVE FIX */
@media (max-width: 480px) {
  .profile-buttons {
    grid-template-columns: 1fr;
    /* Stack buttons on small phones */
  }

  .profile-card {
    padding: 20px;
  }
}
</style>
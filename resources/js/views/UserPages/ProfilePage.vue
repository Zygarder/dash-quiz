<template>
  <div class="profile-page-wrapper">
    <SideBarComp :show="showSidebar" @close="closeSidebar" />
    <!-- Top Bar -->
    <header class="top-bar">
      <button class="menu-btn" @click="toggleSidebar">&#9776;</button>

      <h2>My Profile</h2>

      <router-link to="/" class="logout-btn">
        Log Out
      </router-link>
    </header>

    <main class="main-content">

      <div class="profile-card" v-if="user">

        <!-- Avatar -->
        <div class="profile-header">

          <div class="avatar">
            <img :src="profileImageUrl" draggable="false" />
          </div>

          <h2>{{ user.first_name }}</h2>
          <small>{{ user.email }}</small>

          <form @submit.prevent="handleUpload" enctype="multipart/form-data">
            <label class="file-label">
              Change Profile
              <input type="file" @change="onFileChange">
            </label>

            <button class="upload-btn" :disabled="loading">
              {{ loading ? 'Uploading...' : 'Upload' }}
            </button>
          </form>

        </div>

        <!-- Profile Details -->
        <div class="profile-details">
          <div class="detail-row">
            <span>Full Name:</span>
            <b>{{ fullName }}</b>
          </div>

          <div class="detail-row">
            <span>Date Joined:</span>
            <b>{{ formattedDate }}</b>
          </div>

          <div class="detail-row">
            <span>Quizzes Taken:</span>
            <b>{{ quizzesCount || 'N/A' }}</b>
          </div>
        </div>

        <div class="profile-buttons">
          <button @click="showEditModal = true">Edit Profile</button>
          <button @click="showDeleteModal = true" class="danger">
            Delete Account
          </button>
        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import axios from "axios"
import SideBarComp from "../../components/SideBar.vue"
import { useSidebar } from "@/composables/useSidebar"

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()

const user = ref(null)
const quizzesCount = ref(0)

const showEditModal = ref(false)
const showDeleteModal = ref(false)

const successMessage = ref("")
const errorMessage = ref("")

const loading = ref(false)
const selectedFile = ref(null)

/*
|--------------------------------------------------------------------------
| Fetch Profile
|--------------------------------------------------------------------------
*/

const fetchProfile = async () => {
  try {
    const { data } = await axios.get("/profile")
    user.value = data.data
    quizzesCount.value = data.data.quizzes_taken || 0
  } catch (err) {
    console.error(err)
  }
}

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const fullName = computed(() => {
  if (!user.value) return ""
  return `${user.value.first_name} ${user.value.last_name}`
})

const profileImageUrl = computed(() => {
  if (!user.value) return "/images/profiles/person.jpg"

  return user.value.profile_photo
    ? `/storage/images/profiles/${user.value.profile_photo}`
    : "/images/profiles/person.jpg"
})

const formattedDate = computed(() => {
  if (!user.value) return ""

  const d = new Date(user.value.created_at)

  return d.toLocaleDateString("en-US", {
    month: "short",
    day: "2-digit",
    year: "numeric"
  })
})

/*
|--------------------------------------------------------------------------
| Upload Profile Photo
|--------------------------------------------------------------------------
*/

const onFileChange = (e) => {
  selectedFile.value = e.target.files[0]
}

const handleUpload = async () => {
  if (!selectedFile.value) return

  loading.value = true

  try {
    const formData = new FormData()
    formData.append("myfile", selectedFile.value)

    const { data } = await axios.post("/profile/photo", formData, {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    })

    successMessage.value = "Profile updated!"

    if (data.new_photo) {
      user.value.profile_photo = data.new_photo
    }

  } catch {
    errorMessage.value = "Upload failed."
  } finally {
    loading.value = false
    triggerAlertTimeout()
  }
}

/*
|--------------------------------------------------------------------------
| Utilities
|--------------------------------------------------------------------------
*/

const triggerAlertTimeout = () => {
  setTimeout(() => {
    successMessage.value = ""
    errorMessage.value = ""
  }, 5000)
}
/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
  fetchProfile()
})
</script>

<style scoped>
.profile-card {
  max-width: 600px;
  margin: auto;
  padding: 25px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.avatar img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
}

.file-label {
  display: inline-block;
  padding: 8px 14px;
  background: #eee;
  border-radius: 8px;
  cursor: pointer;
}

.upload-btn {
  margin-left: 10px;
}

.danger {
  background: red;
  color: white;
}
</style>
<template>
  <div class="profile-page-wrapper">
    <SideBarComp :show="showSidebar" @close="closeSidebar" />
    <!-- Top Bar -->
    <header class="top-bar">
      <button class="menu-btn" @click="toggleSidebar">&#9776;</button>

      <h2>My Profile</h2>

      <button @click="handleLogout" class="logout-btn">
        Log Out
      </button>
    </header>

    <main class="main-content">

      <div class="profile-card" v-if="user">

        <!-- Avatar -->
        <div class="profile-header">

          <div class="avatar">
            <div class="avatar">
              <img :src="preview || profileImageUrl" draggable="false" />
            </div>
          </div>

          <h2>{{ user.first_name }}</h2>
          <small>{{ user.email }}</small>

          <form enctype="multipart/form-data">


            <label class="file-label">
              Change Profile
              <input type="file" @change="onFileChange">
            </label>
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
          <button @click="showEditModal = true" id="edit-profile">Edit Profile</button>
          <button @click="showDeleteModal = true" class="danger" id="delete">
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
import { useRouter } from "vue-router"
import { useUser } from "@/composables/useUser"

const router = useRouter()

const { showSidebar, toggleSidebar, closeSidebar } = useSidebar()
const { user, fetchUser, avatar, clearUser } = useUser()

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

// profile data is fetched via composable

const handleLogout = async () => {
  try {

    // correct path for logout API
    await axios.post("/api/logout")

    // clear auth flag
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userRole');

    // clear global user state
    clearUser();

    router.push("/")

  } catch (err) {
    console.error("Logout failed", err)
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

const profileImageUrl = computed(() => avatar.value)

const quizzesCount = computed(() => {
  if (!user.value) return 0

  return user.value.quizzes_taken || 0
})

const formattedDate = computed(() => {
  if (!user.value) return ""

  return user.value.created_at || ""
})

/*
|--------------------------------------------------------------------------
| Upload Profile Photo
|--------------------------------------------------------------------------
*/const preview = ref(null)

const onFileChange = async (e) => {

  const file = e.target.files[0]
  if (!file) return

  selectedFile.value = file

  // preview
  preview.value = URL.createObjectURL(file)

  loading.value = true

  try {

    const formData = new FormData()
    formData.append("photo", file)

    const { data } = await axios.post("/api/profile/photo", formData, {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    })

    successMessage.value = "Profile updated!"

    if (data.photo_url) {
      user.value.profile_photo = data.photo_url
    } else if (data.new_photo) {
      user.value.profile_photo = `/storage/images/profiles/${data.new_photo}`
    }

  } catch (err) {
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

onMounted(async () => {
  await fetchUser()
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
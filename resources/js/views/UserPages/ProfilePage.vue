<template>
  <div class="profile-page-wrapper">
    <SideBarComp :show="showSidebar" @close="closeSidebar" />
    <!-- Top Bar -->
    <TopBar />

    <main class="main-content">

      <div class="profile-card" v-if="user">

        <!-- Avatar -->
        <div class="profile-header">

          <div class="avatar">
            <img :src="preview || profileImageUrl" draggable="false" />
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
            <b>{{ quizzesCount || 0 }}</b>
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
    <EditProfileModal :show="showEditModal" :user="user" @close="showEditModal = false" @save="updateProfile" />

    <DeleteAccountModal :show="showDeleteModal" @close="showDeleteModal = false" @delete="deleteAccount" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import axios from "axios"
import SideBarComp from "../../components/SideBar.vue"
import { useSidebar } from "@/composables/useSidebar"
import { useRouter } from "vue-router"
import { useUser } from "@/composables/useUser"
import DeleteAccountModal from "../../components/ProfileModals/DeleteModal.vue"
import EditProfileModal from "../../components/ProfileModals/EditModal.vue"
import TopBar from "../../components/TopBar.vue"

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



/*
|--------------------------------------------------------------------------
| Edit Modal
|--------------------------------------------------------------------------
*/

const updateProfile = async (formData) => {
  // Reset messages
  successMessage.value = ''
  errorMessage.value = ''

  try {
    // 1. Initialize CSRF protection
    await axios.get('/sanctum/csrf-cookie')

    // 2. Send PUT request to update profile
    const { data } = await axios.put('/api/profile/update', formData)

    // 3. Update local user state
    if (data.user) {
      user.value.first_name = data.user.first_name
      user.value.last_name = data.user.last_name
      user.value.email = data.user.email
    }

    // 4. Close modal and show success
    showEditModal.value = false
    successMessage.value = 'Profile updated successfully!'

  } catch (err) {
    console.error('Profile update failed:', err)

    // 5. Handle validation errors from backend (Laravel typical format)
    if (err.response?.status === 422 && err.response.data.errors) {
      const messages = Object.values(err.response.data.errors)
        .flat()
        .join(' ')
      errorMessage.value = messages
    } else {
      // Generic error
      errorMessage.value = 'Update failed. Please try again.'
    }
  }
}
/*
|--------------------------------------------------------------------------
| Delete Modal
|--------------------------------------------------------------------------
*/
const deleteAccount = async () => {

  try {

    await axios.delete("/api/profile/delete")

    clearUser()

    router.push("/")

  } catch (err) {

    errorMessage.value = "Account deletion failed."

  }

}

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/



const fullName = computed(() => {
  if (!user.value) return ""
  return user.value.full_name
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
/* === PROFILE CARD MAIN CONTAINER === */
.profile-card {
  width: 380px;
  max-width: 90%;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  padding: 2rem 1.5rem;
  margin: 1.5rem auto;
  display: flex;
  flex-direction: column;
  animation: fadeIn 0.25s ease;
}

/* === HEADER: AVATAR + NAME + EMAIL === */
.profile-header {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  margin-bottom: 1.2rem;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  margin-bottom: 0.8rem;
  border: 3px solid #4b32a8;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  user-select: none;
}

.profile-header h2 {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 0.2rem;
}

.profile-header small {
  color: #7d7d7d;
  font-size: 0.85rem;
}

/* === UPLOAD FORM === */
.profile-header form {
  margin-top: 0.8rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
}

.file-label {
  cursor: pointer;
  background: #4b32a8;
  color: #ffffff;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 0.85rem;
  transition: background 0.2s, transform 0.2s;
}

.file-label:hover {
  background: #3a2591;
  transform: translateY(-1px);
}

.file-label input[type="file"] {
  display: none;
}

/* === PROFILE DETAILS SECTION === */
.profile-details {
  margin-top: 1rem;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  background: #f4f2ff;
  padding: 0.7rem 1rem;
  border-radius: 8px;
  font-size: 0.9rem;
  border: 1px solid #e3e0ff;
}

.detail-row span {
  font-weight: 600;
  color: #4b32a8;
}

.detail-row b {
  font-weight: 600;
  color: #333;
}

/* === BUTTON SECTION === */
.profile-buttons {
  margin-top: 1.5rem;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
}

#edit-profile,
#delete {
  cursor: pointer;
  padding: 10px 14px;
  border-radius: 6px;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 0.85rem;
  transition: transform 0.15s, background 0.15s;
}

#edit-profile {
  background-color: #4b32a8;
}

#edit-profile:hover {
  background: #3a2591;
  transform: translateY(-1px);
}

#delete {
  background: #e53935;
}

#delete:hover {
  background: #b71c1c;
  transform: translateY(-1px);
}

/* === RESPONSIVE === */
@media (max-width: 700px) {
  .profile-card {
    width: 95%;
    padding: 1.5rem 1rem;
  }

  .detail-row {
    flex-direction: column;
    gap: 3px;
  }

  .profile-buttons button {
    width: 100%;
  }
}

/* === ANIMATIONS === */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
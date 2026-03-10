<template>
  <div class="profile-page-wrapper">

    <SideBarComp :show="showSidebar" @close="closeSidebar" />

    <TopBar />

    <main class="main-content">

      <div class="profile-card" v-if="user">

        <!-- Avatar -->
        <div class="profile-header">

          <div class="avatar">
            <img
              :src="preview || profileImageUrl"
              draggable="false"
              @error="setDefaultAvatar"
            />
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
            <b>{{ quizzesCount }}</b>
          </div>

        </div>

        <div class="profile-buttons">

          <button
            id="edit-profile"
            @click="showEditModal = true">
            Edit Profile
          </button>

          <button
            id="delete"
            class="danger"
            @click="showDeleteModal = true">
            Delete Account
          </button>

        </div>

      </div>

    </main>

    <!-- Modals -->
    <EditProfileModal
      :show="showEditModal"
      :user="user"
      @close="showEditModal = false"
      @save="updateProfile"
    />

    <DeleteAccountModal
      :show="showDeleteModal"
      @close="showDeleteModal = false"
      @delete="deleteAccount"
    />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

import SideBarComp from "../../components/SideBar.vue"
import TopBar from "../../components/TopBar.vue"
import DeleteAccountModal from "../../components/ProfileModals/DeleteModal.vue"
import EditProfileModal from "../../components/ProfileModals/EditModal.vue"

import { useSidebar } from "@/composables/useSidebar"
import { useUser } from "@/composables/useUser"

const router = useRouter()

const { showSidebar, closeSidebar } = useSidebar()
const { user, fetchUser, avatar, clearUser } = useUser()

const showEditModal = ref(false)
const showDeleteModal = ref(false)

const preview = ref(null)
const selectedFile = ref(null)

const successMessage = ref("")
const errorMessage = ref("")
const loading = ref(false)

/*
|--------------------------------------------------------------------------
| Profile Computed
|--------------------------------------------------------------------------
*/

const fullName = computed(() => {
  if (!user.value) return ""
  return user.value.full_name
})

const profileImageUrl = computed(() => avatar.value)

const quizzesCount = computed(() => {
  return user.value?.quizzes_taken || 0
})

const formattedDate = computed(() => {
  if (!user.value?.created_at) return ""

  return new Date(user.value.created_at).toLocaleDateString()
})

/*
|--------------------------------------------------------------------------
| Avatar Fallback
|--------------------------------------------------------------------------
*/

const setDefaultAvatar = (event) => {
  event.target.src = "/storage/images/profiles/default.png"
}

/*
|--------------------------------------------------------------------------
| Update Profile
|--------------------------------------------------------------------------
*/

const updateProfile = async (formData) => {

  successMessage.value = ""
  errorMessage.value = ""

  try {

    await axios.get("/sanctum/csrf-cookie")

    const { data } = await axios.put("/api/profile/update", formData)

    if (data.user) {

      user.value.first_name = data.user.first_name
      user.value.last_name = data.user.last_name
      user.value.email = data.user.email

    }

    showEditModal.value = false
    successMessage.value = "Profile updated successfully!"

  } catch (err) {

    if (err.response?.status === 422) {

      const messages = Object.values(err.response.data.errors)
        .flat()
        .join(" ")

      errorMessage.value = messages

    } else {

      errorMessage.value = "Update failed."

    }

  }

}

/*
|--------------------------------------------------------------------------
| Delete Account
|--------------------------------------------------------------------------
*/

const deleteAccount = async () => {

  try {

    await axios.delete("/api/profile/delete")

    clearUser()

    router.push("/")

  } catch {

    errorMessage.value = "Account deletion failed."

  }

}

/*
|--------------------------------------------------------------------------
| Upload Photo
|--------------------------------------------------------------------------
*/

const onFileChange = async (e) => {

  const file = e.target.files[0]
  if (!file) return

  selectedFile.value = file
  preview.value = URL.createObjectURL(file)

  loading.value = true

  try {

    const formData = new FormData()
    formData.append("photo", file)

    const { data } = await axios.post("/api/profile/photo", formData, {
      headers: { "Content-Type": "multipart/form-data" }
    })

    successMessage.value = "Profile updated!"

    if (data.photo_url) {
      user.value.profile_photo = data.photo_url
    }

  } catch {

    errorMessage.value = "Upload failed."

  } finally {

    loading.value = false

  }

}

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(async () => {

  if (!user.value) {
    await fetchUser()
  }

})
</script>

<style scoped>

/* PAGE WRAPPER */
.profile-page-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: #f6f7fb;
}

/* MAIN CONTENT */
.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
}

/* PROFILE CARD */
.profile-card {
  width: 380px;
  max-width: 95%;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  padding: 2rem 1.5rem;
  display: flex;
  flex-direction: column;
  animation: fadeIn .25s ease;
}

/* HEADER */
.profile-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap:10px;
  margin-bottom: 1.2rem;
}

/* AVATAR */
.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #4b32a8;
  margin-bottom: 10px;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* NAME */
.profile-header h2 {
  font-size: 1.4rem;
  font-weight: 700;
}

.profile-header small {
  color: #777;
}

/* FILE LABEL */
.file-label {
  cursor: pointer;
  background: #4b32a8;
  color: #fff;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: .85rem;
  margin-top: 8px;
}

.file-label input {
  display: none;
}

/* DETAILS */
.profile-details {
  margin-top: 1rem;
  display: flex;
  flex-direction: column;
  gap: .6rem;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  background: #f4f2ff;
  padding: .7rem 1rem;
  border-radius: 8px;
  border: 1px solid #e3e0ff;
}

.detail-row span {
  font-weight: 600;
  color: #4b32a8;
}

/* BUTTONS */
.profile-buttons {
  margin-top: 1.5rem;
  display: flex;
  gap: 10px;
  justify-content: center;
}

#edit-profile,
#delete {
  border: none;
  padding: 10px 14px;
  border-radius: 6px;
  color: white;
  font-weight: 600;
  cursor: pointer;
}

#edit-profile {
  background: #4b32a8;
}

#delete {
  background: #e53935;
}

/* ANIMATION */
@keyframes fadeIn {
  from { opacity:0; transform:translateY(6px); }
  to { opacity:1; transform:translateY(0); }
}

/* MOBILE */
@media (max-width:700px) {

  .profile-card {
    width: 95%;
  }

  .detail-row {
    flex-direction: column;
    gap: 4px;
  }

}

</style>
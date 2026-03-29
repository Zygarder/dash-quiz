<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  show: Boolean,
  user: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save', 'notify'])

// ✅ Local form state
const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

// ✅ Watch for when the modal opens to sync data
watch(
  () => props.show,
  (isVisible) => {
    if (isVisible && props.user) {
      form.value = {
        first_name: props.user.first_name || '',
        last_name: props.user.last_name || '',
        email: props.user.email || '',
        password: '', // Reset passwords
        password_confirmation: ''
      }
    }
  },
  { immediate: true }
)

// ✅ Save handler
const handleSave = () => {
  // 1. Password Match Validation
  if (form.value.password || form.value.password_confirmation) {
    if (form.value.password !== form.value.password_confirmation) {
      emit('notify', { message: "Passwords do not match!", type: "error" })
      return
    }
  }

  // 2. Prepare Clean Payload
  const payload = { ...form.value }

  // 3. Remove password fields if they are empty (so we don't update them)
  if (!payload.password) {
    delete payload.password
    delete payload.password_confirmation
  }

  emit('save', payload)
  emit('close')
}
</script>

<template>
  <div v-if="show" class="modal">
    <div class="modal-content">
      <h3>Edit Profile</h3>
      <p>Update your information below.</p>

      <div class="edit-fields">
        <label for="first_name">First Name</label>
        <input id="first_name" v-model="form.first_name" type="text" autocomplete="off" />
      </div>

      <div class="edit-fields">
        <label for="last_name">Last Name</label>
        <input id="last_name" v-model="form.last_name" type="text" autocomplete="off" />
      </div>

      <div class="edit-fields">
        <label for="email">Email</label>
        <input id="email" v-model="form.email" type="email" autocomplete="off" />
      </div>

      <div class="edit-fields">
        <label for="password">Password</label>
        <input id="password" v-model="form.password" type="password" autocomplete="off" />
      </div>

      <div class="edit-fields">
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="off" />
      </div>

      <div class="modal-buttons">
        <button class="cancel-btn" @click="$emit('close')">Cancel</button>
        <button class="save-btn" @click="handleSave">Save Changes</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Reusing the core alignment logic */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1004;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 400px;
  max-width: 90%;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

/* New Input Styles */
.edit-fields {
  display: flex;
  flex-direction: column;
  text-align: left;
  margin: 10px 0;
}

.edit-fields label {
  font-size: 0.9rem;
  font-weight: bold;
  margin-bottom: 6px;
  color: #555;
}

.edit-fields input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
}

/* Button Alignment */
.modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

button {
  padding: 10px 18px;
  cursor: pointer;
  border-radius: 6px;
  border: none;
  font-weight: 600;
}

.cancel-btn {
  background-color: #f3f4f6;
  color: #4b5563;
}

.save-btn {
  background-color: #3b82f6;
  /* Blue for "Save" */
  color: white;
}

.save-btn:hover {
  background-color: #2563eb;
}
</style>
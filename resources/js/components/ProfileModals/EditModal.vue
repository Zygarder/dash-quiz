<template>
  <div v-if="show" class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-card">
      <header class="modal-header">
        <h3>Edit Profile</h3>
        <p>Manage your account settings and email preferences.</p>
      </header>

      <div class="modal-body">
        <div class="form-grid">
          <div class="field">
            <label for="first_name">First Name</label>
            <input id="first_name" v-model="form.first_name" type="text" placeholder="Jane" />
          </div>

          <div class="field">
            <label for="last_name">Last Name</label>
            <input id="last_name" v-model="form.last_name" type="text" placeholder="Doe" />
          </div>
        </div>

        <div class="field">
          <label for="email">Email Address</label>
          <input id="email" v-model="form.email" type="email" />
        </div>

        <hr class="divider" />

        <div class="field">
          <label for="password">Current Password</label>
          <input id="password" v-model="form.password" type="password" placeholder="••••••••" />
        </div>

        <div class="form-grid">
          <div class="field">
            <label for="new_password">New Password</label>
            <input id="new_password" v-model="form.new_password" type="password" />
          </div>

          <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" v-model="form.new_password_confirmation" type="password" />
          </div>
        </div>
      </div>

      <footer class="modal-footer">
        <button class="btn btn-ghost" @click="$emit('close')">Cancel</button>
        <button class="btn btn-primary" @click="handleSave">Save Changes</button>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  show: Boolean,
  loading: Boolean,
  user: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['save', 'close', 'notify'])

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  new_password: '',
  new_password_confirmation: ''
})

watch(
  () => props.user,
  (user) => {
    if (user) {
      form.value = {
        first_name: user.first_name ?? '',
        last_name: user.last_name ?? '',
        email: user.email ?? '',
        password: '',
        new_password: '',
        new_password_confirmation: ''
      }
    }
  },
  { immediate: true }
)

const handleSave = () => {
  // Client-side password match check
  if (form.value.new_password || form.value.new_password_confirmation) {
    if (form.value.new_password !== form.value.new_password_confirmation) {
      emit('notify', { message: 'Passwords do not match.', type: 'error' })
      return
    }
  }

  const payload = {
    first_name: form.value.first_name,
    last_name: form.value.last_name,
    email: form.value.email,
  }

  // Only include password fields if the user actually filled them
  if (form.value.new_password) {
    payload.password = form.value.new_password
    payload.password_confirmation = form.value.new_password_confirmation
  }

  // Let the parent handle close — after the API call resolves
  emit('save', payload)
}
</script>

<style scoped>
/* Overlay remains the same as requested */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1004;
  padding: 20px;
}

/* Modern Minimal Card */
.modal-card {
  background: #ffffff;
  width: 100%;
  max-width: 480px;
  border-radius: 12px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-header {
  padding: 24px 24px 16px;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.modal-header p {
  margin: 4px 0 0;
  font-size: 0.875rem;
  color: #6b7280;
}

.modal-body {
  padding: 0 24px 24px;
}

/* Layout Grid */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.field {
  display: flex;
  flex-direction: column;
  margin-top: 16px;
}

.divider {
  border: 0;
  border-top: 1px solid #f3f4f6;
  margin: 24px 0 8px;
}

/* Inputs */
label {
  font-size: 0.813rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 6px;
}

input {
  appearance: none;
  background-color: #fff;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 8px 12px;
  font-size: 0.938rem;
  color: #1f2937;
  transition: all 0.2s ease;
}

input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input::placeholder {
  color: #9ca3af;
}

/* Footer & Buttons */
.modal-footer {
  padding: 16px 24px;
  background-color: #f9fafb;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn {
  font-size: 0.875rem;
  font-weight: 600;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.2s;
  border: 1px solid transparent;
}

.btn-ghost {
  background: transparent;
  color: #4b5563;
}

.btn-ghost:hover {
  background: #f3f4f6;
}

.btn-primary {
  background-color: #111827;
  /* Modern dark neutral instead of bright blue */
  color: white;
}

.btn-primary:hover {
  background-color: #1f2937;
}
</style>
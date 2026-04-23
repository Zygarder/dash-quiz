<template>
  <div v-if="show" class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-card destructive">
      <div class="modal-body">
        <div class="icon-header">
          <div class="warning-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
          </div>
          <div class="header-text">
            <h3>Delete Account</h3>
            <p>This action is permanent and cannot be undone. All your data will be wiped immediately.</p>
          </div>
        </div>

        <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>
      </div>

      <footer class="modal-footer">
        <button class="btn btn-ghost" :disabled="isSubmitting" @click="$emit('close')">
          Keep Account
        </button>
        <button 
          class="btn btn-danger" 
          :disabled="isSubmitting" 
          @click="handleDelete"
        >
          {{ isSubmitting ? 'Deleting...' : "Delete Everything" }}
        </button>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import axios from "axios"
import { useUser } from "@/composables/useUser"

const props = defineProps({
  show: Boolean
})

const emit = defineEmits(["close", "deleted"])
const { clearUser } = useUser()

const isSubmitting = ref(false)
const errorMessage = ref("")

const handleDelete = async () => {
  isSubmitting.value = true
  errorMessage.value = ""

  try {
    await axios.delete("/api/profile/delete")
    clearUser()
    emit("deleted")
  } catch (err) {
    errorMessage.value = "We couldn't delete your account. Please try again."
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(15, 23, 42, 0.6); /* Slate overlay */
  backdrop-filter: blur(2px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1004;
  padding: 20px;
}

.modal-card {
  background: #ffffff;
  width: 100%;
  max-width: 440px;
  border-radius: 12px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.modal-body {
  padding: 24px;
}

.icon-header {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

.warning-icon {
  background-color: #fee2e2;
  color: #dc2626;
  padding: 10px;
  border-radius: 50%;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.header-text h3 {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.header-text p {
  margin: 8px 0 0;
  font-size: 0.875rem;
  line-height: 1.5;
  color: #6b7280;
}

.error-text {
  margin-top: 16px;
  font-size: 0.875rem;
  color: #dc2626;
  background: #fef2f2;
  padding: 8px 12px;
  border-radius: 6px;
}

.modal-footer {
  padding: 16px 24px;
  background-color: #f9fafb;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

/* Button Refinement */
.btn {
  font-size: 0.875rem;
  font-weight: 600;
  padding: 10px 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid transparent;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-ghost {
  background: white;
  border: 1px solid #d1d5db;
  color: #374151;
}

.btn-ghost:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #c5cacc;
}

.btn-danger {
  background-color: #dc2626;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background-color: #b91c1c;
}
</style>
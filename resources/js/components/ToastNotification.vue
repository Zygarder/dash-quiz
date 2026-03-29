<template>
  <Transition name="toast">
    <div v-if="message" :class="['toast-box', type]">
      <div class="toast-content">
        <i v-if="type === 'success'" class="fa-solid fa-check"></i>
        <i v-else class="fa-solid fa-xmark"></i>
        <p>{{ message }}</p>
      </div>
      <button @click="$emit('clear')" class="close-btn">&times;</button>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  message: String,
  type: { type: String, default: 'success' } // 'success' or 'error'
})
defineEmits(['clear'])
</script>

<style scoped>
.toast-box {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 100%;
  max-width: 320px;
  padding: 10px 16px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 9999;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  color: white;
}

.success {
  background: #10b981;
  border-left: 5px solid #059669;
}

.error {
  background: #ef4444;
  border-left: 5px solid #dc2626;
}

.toast-content {
  display: flex;
  align-items: center;
  gap: 12px;
}

.toast-content p {
  margin: 0;
  font-weight: 600;
  font-size: 0.9rem;
}

.close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.2rem;
  cursor: pointer;
  opacity: 0.7;
}

.close-btn:hover {
  opacity: 1;
}

/* Animation */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.toast-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
<template>
  <Transition name="toast">
    <div v-if="message" :class="['toast-box', type]">

      <!-- Icon -->
      <div class="toast-icon">
        <i v-if="type === 'success'" class="fa-solid fa-check"></i>
        <i v-else class="fa-solid fa-xmark"></i>
      </div>

      <!-- Message -->
      <div class="toast-content">
        <p>{{ message }}</p>
      </div>

      <!-- Close -->
      <button @click="$emit('clear')" class="close-btn">
        <i class="fa-solid fa-xmark"></i>
      </button>

    </div>
  </Transition>
</template>

<script setup>
defineProps({
  message: String,
  type: { type: String, default: 'success' }
})

defineEmits(['clear'])
</script>

<style scoped>
/* =========================
   TOAST BASE
========================= */
.toast-box {
  position: fixed;
  top: 20px;
  right: 20px;
  width: min(360px, calc(100% - 40px));
  display: flex;
  align-items: center;
  gap: 12px;

  padding: 12px 14px;
  border-radius: 12px;

  background: #ffffff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);

  z-index: 9999;
}

/* =========================
   TYPES (minimal tint style)
========================= */
.success {
  border-left: 4px solid #10b981;
}

.error {
  border-left: 4px solid #ef4444;
}

/* =========================
   ICON
========================= */
.toast-icon {
  width: 34px;
  height: 34px;
  border-radius: 10px;

  display: flex;
  align-items: center;
  justify-content: center;

  font-size: 0.9rem;
  color: white;
  flex-shrink: 0;
}

.success .toast-icon {
  background: #10b981;
}

.error .toast-icon {
  background: #ef4444;
}

/* =========================
   MESSAGE
========================= */
.toast-content {
  flex: 1;
}

.toast-content p {
  margin: 0;
  font-size: 0.85rem;
  font-weight: 500;
  color: #111827;
  line-height: 1.3;
}

/* =========================
   CLOSE BUTTON
========================= */
.close-btn {
  background: transparent;
  border: none;
  cursor: pointer;

  width: 30px;
  height: 30px;

  border-radius: 8px;
  color: #6b7280;

  display: flex;
  align-items: center;
  justify-content: center;

  transition: 0.2s ease;
}

.close-btn:hover {
  background: #f3f4f6;
  color: #111827;
}

/* =========================
   ANIMATION
========================= */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.25s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateY(-10px) scale(0.98);
}

.toast-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(0.98);
}

/* =========================
   MOBILE
========================= */
@media (max-width: 480px) {
  .toast-box {
    right: 10px;
    left: 10px;
    width: auto;
  }
}
</style>
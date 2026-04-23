<template>
  <Transition name="toast">
    <div v-if="message" :class="['toast-box', type]">

      <div class="toast-icon">
        <i v-if="type === 'success'" class="fa-solid fa-check"></i>
        <i v-else class="fa-solid fa-xmark"></i>
      </div>

      <div class="toast-content">
        <span class="toast-title">{{ type === 'success' ? 'Success' : 'Error' }}</span>
        <p>{{ message }}</p>
      </div>

      <button @click="$emit('clear')" class="close-btn">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <div class="toast-progress" />

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
.toast-box {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 100%;
  max-width: min(350px, calc(100% - 40px));
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 13px 13px;
  border-radius: 10px;
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.07);
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
  z-index: 9999;
  overflow: hidden;
}

.success { border-left: 3.5px solid #10b981; }
.error   { border-left: 3.5px solid #ef4444; }

/* Icon */
.toast-icon {
  width: 32px;
  height: 32px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  color: #fff;
  flex-shrink: 0;
}

.success .toast-icon { background: #10b981; }
.error   .toast-icon { background: #ef4444; }

/* Content */
.toast-content {
  flex: 1;
}

.toast-title {
  display: block;
  font-size: 0.78rem;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 1px;
}

.toast-content p {
  margin: 0;
  font-size: 0.78rem;
  color: #6b7280;
  line-height: 1.35;
}

/* Close */
.close-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  color: #9ca3af;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  flex-shrink: 0;
  transition: background 0.15s, color 0.15s;
}

.close-btn:hover {
  background: #f3f4f6;
  color: #374151;
}

/* Progress bar */
.toast-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 2.5px;
  width: 100%;
  border-radius: 0 0 14px 14px;
  transform-origin: left;
  animation: shrink 2s linear forwards;
}

.success .toast-progress { background: #10b981; }
.error   .toast-progress { background: #ef4444; }

/* Animation */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.28s cubic-bezier(0.22, 0.68, 0, 1.2);
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(0.97);
}

@keyframes shrink {
  from { transform: scaleX(1); }
  to   { transform: scaleX(0); }
}

/* Responsive */
@media (max-width: 480px) {
  .toast-box {
    top: 12px;
    right: 12px;
    left: 12px;
    width: auto;
  }
}
</style>
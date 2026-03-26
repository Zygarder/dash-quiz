<template>
  <header class="admin-header">
    <div class="header-left">
      <button class="menu-toggle" @click="$emit('toggleSidebar')" aria-label="Toggle menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round">
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
      </button>
      <div class="breadcrumb">
        <span class="breadcrumb-root">Admin</span>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">{{ currentPageTitle }}</span>
      </div>
    </div>

    <div class="header-right">
      <button class="icon-btn" title="Notifications">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        <span class="notif-dot"></span>
      </button>
      <div class="header-divider"></div>
      <div class="header-user">
        <div class="header-avatar">A</div>
        <div class="header-user-info">
          <span class="header-user-name">Admin</span>
          <span class="header-user-status"><span class="status-dot"></span>Online</span>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
defineProps({
  currentPageTitle: String
})
</script>

<style scoped>
/* Keep your admin-header styles from original code */
</style>
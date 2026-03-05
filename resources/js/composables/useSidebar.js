import { ref } from "vue"

export function useSidebar() {

  const showSidebar = ref(false)

  const toggleSidebar = () => {
    showSidebar.value = !showSidebar.value
  }

  const closeSidebar = () => {
    showSidebar.value = false
  }

  return {
    showSidebar,
    toggleSidebar,
    closeSidebar
  }
}
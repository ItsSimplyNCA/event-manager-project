import { onMounted, onUnmounted } from 'vue'

export function usePolling(callback, interval = 5000) {
  let intervalId = null

  onMounted(() => {
    intervalId = setInterval(callback, interval)
  })

  onUnmounted(() => {
    if (intervalId) {
      clearInterval(intervalId)
    }
  })
}
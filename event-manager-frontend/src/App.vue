<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'
import AppButton from './components/ui/AppButton.vue'

const router = useRouter()
const authStore = useAuthStore()

async function logout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="app-shell">
    <header class="topbar">
      <nav class="nav" v-if="authStore.initialized">
        <template v-if="authStore.isAuthenticated">
          <RouterLink to="/events">Events</RouterLink>
          <RouterLink to="/support">Support</RouterLink>
          <RouterLink v-if="authStore.isAgent" to="/agents/chats">Agent</RouterLink>
          <AppButton variant="secondary" @click="logout">Logout</AppButton>>
        </template>

        <template v-else>
          <RouterLink to="/login">Login</RouterLink>
        </template>
      </nav>
    </header>

    <main class="page">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.app-shell {
  max-width: 980px;
  margin: 0 auto;
  padding: 24px;
  font-family: Arial, sans-serif;
}

.topbar {
  margin-bottom: 24px;
}

.nav {
  display: flex;
  gap: 12px;
  align-items: center;
}

.page {
  display: block;
}
</style>

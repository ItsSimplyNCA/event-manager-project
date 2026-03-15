<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { getErrorMessage, getValidationErrors, firstFieldError } from '../utils/apiErrors'
import AppButton from '../components/ui/AppButton.vue'
import AppCard from '../components/ui/AppCard.vue'
import FieldError from '../components/ui/FieldError.vue'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('user@example.com')
const password = ref('password123')
const error = ref('')
const fieldErrors = ref({})
const loading = ref(false)

async function submit() {
  error.value = ''
  fieldErrors.value = {}
  loading.value = true

  try {
    const user = await authStore.login({
      email: email.value,
      password: password.value,
    })

    router.push(user.role === 'agent' ? '/agent/chats' : '/events')
  } catch (err) {
    fieldErrors.value = getValidationErrors(err)
    error.value = getErrorMessage(err, 'Failed to login.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="stack">
    <h1 class="page-title">Login</h1>

    <AppCard>
      <form @submit.prevent="submit">
        <div class="field">
          <label>Email</label>
          <input v-model="email" type="email" />
          <FieldError :message="firstFieldError(fieldErrors, 'email')" />
        </div>

        <div class="field">
          <label>Password</label>
          <input v-model="password" type="password" />
          <FieldError :message="firstFieldError(fieldErrors, 'password')" />
        </div>

        <div class="button-row">
          <AppButton :disabled="loading" type="submit">
            {{ loading ? 'Logging in...' : 'Login' }}
          </AppButton>
        </div>
      </form>

      <p v-if="error" class="error-text">{{ error }}</p>
      <p class="helper-text">Test User: user@example.com / password123</p>
      <RouterLink to="/forgot-password">Forgotten password</RouterLink>
    </AppCard>
  </section>
</template>
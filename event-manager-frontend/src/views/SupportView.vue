<script setup>
import { onMounted, ref } from 'vue'
import api from '../lib/api'
import ChatMessages from '../components/support/ChatMessages.vue'
import { usePolling } from '../composables/usePolling'
import AppButton from '../components/ui/AppButton.vue'
import AppCard from '../components/ui/AppCard.vue'

const chat = ref(null)
const message = ref('')
const error = ref('')
const loading = ref(false)

async function loadChat() {
  loading.value = true
  error.value = ''

  try {
    const { data } = await api.get('/api/support/chat')
    chat.value = data.data
  } catch (err) {
    error.value =
      err?.response?.data?.message || 'Nem sikerült betölteni a support chatet.'
  } finally {
    loading.value = false
  }
}

async function sendMessage() {
  if (!message.value.trim()) return

  error.value = ''

  try {
    const { data } = await api.post('/api/support/chat/messages', {
      message: message.value,
    })

    chat.value = data.data
    message.value = ''
  } catch (err) {
    error.value =
      err?.response?.data?.message || 'Nem sikerült elküldeni az üzenetet.'
  }
}

onMounted(loadChat)
usePolling(loadChat, 5000)
</script>

<template>
  <section class="stack">
    <h1 class="page-title">Support</h1>

    <p v-if="loading && !chat">Loading...</p>
    <p v-if="error" class="error-text">{{ error }}</p>

    <AppCard v-if="chat">
      <p><strong>Status:</strong> {{ chat.status || 'Unknown' }}</p>

      <p v-if="chat.assigned_agent" class="helper-text">
        <strong>Agent:</strong> {{ chat.assigned_agent.name }}
      </p>

      <div v-if="Array.isArray(chat.messages)" style="margin-top: 16px;">
        <ChatMessages :messages="chat.messages" />
      </div>
    </AppCard>

    <AppCard>
      <form @submit.prevent="sendMessage">
        <div class="field">
          <label>Message</label><br />
          <textarea
            v-model="message"
            rows="4"
            placeholder="Write your problem..."
          ></textarea>
        </div>

        <div class="button-row">
          <AppButton type="submit">Submit</AppButton>
        </div>
      </form>
    </AppCard>
  </section>
</template>
<script setup>
import { computed, onMounted, ref } from 'vue'
import api from '../lib/api'
import ChatMessages from '../components/support/ChatMessages.vue'
import { usePolling } from '../composables/usePolling'
import { getErrorMessage } from '../utils/apiErrors'
import AppButton from '../components/ui/AppButton.vue'
import AppCard from '../components/ui/AppCard.vue'

const chats = ref([])
const selectedChatId = ref(null)
const reply = ref('')
const error = ref('')
const loading = ref(false)

const selectedChat = computed(() => {
  return chats.value.find((chat) => chat.id === selectedChatId.value) || null
})

async function loadChats() {
  loading.value = true
  error.value = ''

  try {
    const { data } = await api.get('/api/agent/chats')
    chats.value = data.data

    if (!selectedChatId.value && chats.value.length > 0) {
      selectedChatId.value = chats.value[0].id
    }

    if (
      selectedChatId.value &&
      !chats.value.some((chat) => chat.id === selectedChatId.value)
    ) {
      selectedChatId.value = chats.value[0]?.id || null
    }
  } catch (err) {
    error.value = getErrorMessage(err, 'Nem sikerült betölteni a chateket.')
  } finally {
    loading.value = false
  }
}

async function assignChat(chatId) {
  error.value = ''

  try {
    const { data } = await api.post(`/api/agent/chats/${chatId}/assign`)
    const updatedChat = data.data

    chats.value = chats.value.map((chat) =>
      chat.id === updatedChat.id ? updatedChat : chat
    )

    selectedChatId.value = updatedChat.id
  } catch (err) {
    error.value = getErrorMessage(err, 'Failed to assign chat.')
  }
}

async function sendReply() {
  if (!selectedChat.value || !reply.value.trim()) return

  error.value = ''

  try {
    const { data } = await api.post(
      `/api/agent/chats/${selectedChat.value.id}/messages`,
      { message: reply.value }
    )

    const updatedChat = data.data

    chats.value = chats.value.map((chat) =>
      chat.id === updatedChat.id ? updatedChat : chat
    )

    reply.value = ''
  } catch (err) {
    error.value = getErrorMessage(err, 'Failed to send reply.')
  }
}

function selectChat(chatId) {
  selectedChatId.value = chatId
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString()
}

function canTakeChat(chat) {
  return !chat.assigned_agent_id
}

onMounted(loadChats)
usePolling(loadChats, 5000)
</script>

<template>
  <section class="stack">
    <h1 class="page-title">Agent chatek</h1>

    <p v-if="loading && chats.length === 0">Loading...</p>
    <p v-if="error" class="error-text">{{ error }}</p>

    <div class="grid-2">
      <AppCard>
        <h2>Open Chats</h2>

        <p v-if="!loading && chats.length === 0" class="helper-text">
          Unopened chat.
        </p>

        <div class="list">
          <div
            v-for="chat in chats"
            :key="chat.id"
            @click="selectChat(chat.id)"
            :class="['sidebar-item', { active: selectedChatId === chat.id }]"
          >
            <div><strong>{{ chat.user.name }}</strong></div>
            <div>{{ chat.user.email }}</div>
            <div class="helper-text">Státusz: {{ chat.status }}</div>
            <div v-if="chat.last_message_at" class="helper-text">
              Last activity: {{ formatDate(chat.last_message_at) }}
            </div>
            <div v-if="chat.assigned_agent" class="helper-text">
              Agent: {{ chat.assigned_agent.name }}
            </div>
          </div>
        </div>
      </AppCard>

      <AppCard v-if="selectedChat">
        <h2>{{ selectedChat.user.name }}</h2>
        <p class="helper-text">{{ selectedChat.user.email }}</p>
        <p><strong>Status:</strong> {{ selectedChat.status }}</p>

        <p v-if="selectedChat.assigned_agent" class="helper-text">
          <strong>Agent:</strong> {{ selectedChat.assigned_agent.name }}
        </p>

        <div class="button-row" style="margin-bottom: 16px;">
          <AppButton
            v-if="canTakeChat(selectedChat)"
            @click="assignChat(selectedChat.id)"
          >
            Assign it to myself
          </AppButton>
        </div>

        <div style="margin-bottom: 16px;">
          <ChatMessages :messages="selectedChat.messages" />
        </div>

        <form @submit.prevent="sendReply">
          <div class="field">
            <label>Válasz</label>
            <textarea
              v-model="reply"
              rows="4"
              placeholder="Write your reply..."
            ></textarea>
          </div>

          <div class="button-row">
            <AppButton type="submit">Submit Reply</AppButton>
          </div>
        </form>
      </AppCard>

      <AppCard v-else>
        <p class="helper-text">Select a chat from the list.</p>
      </AppCard>
    </div>
  </section>
</template>
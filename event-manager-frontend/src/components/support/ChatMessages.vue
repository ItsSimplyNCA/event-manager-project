<script setup>
defineProps({
  messages: {
    type: Array,
    required: true,
  },
})

function formatDate(dateString) {
  return new Date(dateString).toLocaleString()
}
</script>

<template>
  <div class="list">
    <div
      v-for="msg in messages"
      :key="msg.id"
      class="chat-message"
    >
      <div class="chat-message-meta">
        <strong>{{ msg.sender_type }}</strong>
        <span style="margin-left: 8px">
          {{ formatDate(msg.created_at) }}
        </span>
      </div>

      <div>{{ msg.body }}</div>
    </div>
  </div>
</template>
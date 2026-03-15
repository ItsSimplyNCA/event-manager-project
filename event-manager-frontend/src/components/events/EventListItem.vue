<script setup>
import { ref, watch } from 'vue'
import AppButton from '../ui/AppButton.vue'
import AppCard from '../ui/AppCard.vue'

const props = defineProps({
  event: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['save-description', 'delete'])

const description = ref(props.event.description || '')

watch(
  () => props.event.description,
  (newValue) => {
    description.value = newValue || ''
  }
)

function save() {
  emit('save-description', {
    id: props.event.id,
    description: description.value,
  })
}

function remove() {
  emit('delete', props.event.id)
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString()
}
</script>

<template>
  <AppCard>
    <h3>{{ event.title }}</h3>
    <p class="helper-text">
      <strong>Date:</strong> {{ formatDate(event.occurs_at) }}
    </p>

    <div class="field">
      <label>Description</label>
      <textarea v-model="description"></textarea>
    </div>

    <div class="button-row">
      <AppButton @click="save">Save Description</AppButton>
      <AppButton variant="danger" @click="remove">Delete</AppButton>
    </div>
  </AppCard>
</template>
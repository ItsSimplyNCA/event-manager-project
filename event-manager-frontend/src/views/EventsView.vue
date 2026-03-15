<script setup>
import { onMounted, ref } from 'vue'
import api from '../lib/api'
import EventCreateForm from '../components/events/EventCreateForm.vue'
import EventListItem from '../components/events/EventListItem.vue'
import { getErrorMessage, getValidationErrors, firstFieldError } from '../utils/apiErrors' 

const events = ref([])
const loading = ref(false)
const error = ref('')
const createErrors = ref({})

async function loadEvents() {
  loading.value = true
  error.value = ''

  try {
    const { data } = await api.get('/api/events')
    events.value = data.data
  } catch (err) {
    error.value = getErrorMessage(err, 'Failed to load events.')
  } finally {
    loading.value = false
  }
}

async function createEvent(payload) {
  error.value = ''
  createErrors.value = {}

  try {
    await api.post('/api/events', payload)
    await loadEvents()
  } catch (err) {
    createErrors.value = getValidationErrors(err)
    error.value = getErrorMessage(err, 'Failed to create event.')
  }
}

async function saveDescription(payload) {
  error.value = ''

  try {
    await api.patch(`/api/events/${payload.id}`, {
      description: payload.description,
    })

    await loadEvents()
  } catch (err) {
    error.value = getErrorMessage(err, 'Failed to save description.')
  }
}

async function deleteEvent(eventId) {
  error.value = ''

  try {
    await api.delete(`/api/events/${eventId}`)
    await loadEvents()
  } catch (err) {
    error.value = getErrorMessage(err, 'Failed to delete event.')
  }
}

onMounted(loadEvents)
</script>

<template>
  <section>
    <h1>Events</h1>

    <EventCreateForm @submit="createEvent" />

    <hr />

    <div>
      <h2>My Events</h2>

      <p v-if="loading">Loading...</p>
      <p v-if="error">{{ error }}</p>

      <EventListItem
        v-for="event in events"
        :key="event.id"
        :event="event"
        @save-description="saveDescription"
        @delete="deleteEvent"
      />
    </div>
  </section>
</template>
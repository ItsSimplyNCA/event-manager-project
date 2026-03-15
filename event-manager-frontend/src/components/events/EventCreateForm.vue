<script setup>
import { ref } from 'vue'
import { firstFieldError } from '../../utils/apiErrors'
import AppButton from '../ui/AppButton.vue'
import AppCard from '../ui/AppCard.vue'
import FieldError from '../ui/FieldError.vue'


const props = defineProps({
  errors: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits(['submit'])

const form = ref({
    title: '',
    occurs_at: '',
    description: '',
})

function submit() {
    emit('submit', {
        title: form.value.title,
        occurs_at: form.value.occurs_at,
        description: form.value.description,
    })

    form.value = {
        title: '',
        occurs_at: '',
        description: '',
    }
}
</script>

<template>
  <AppCard>
    <h2>New Event</h2>

    <form @submit.prevent="submit">
      <div class="field">
        <label>Title</label>
        <input v-model="form.title" type="text"/>
        <FieldError :message="firstFieldError(errors, 'title')"/>
      </div>

      <div class="field">
        <label>Date</label><br />
        <input v-model="form.occurs_at" type="datetime-local" />
        <FieldError :message="firstFieldError(errors, 'occurs_at')"/>
      </div>

      <div class="field">
        <label>Description</label><br />
        <textarea v-model="form.description"></textarea>
      </div>

      <div class="button-row">
        <AppButton type="submit">Create</AppButton>
      </div>
    </form>
  </AppCard>
</template>
<script setup>
import { ref } from 'vue'
import api from '../lib/api'
import { getErrorMessage, getValidationErrors, firstFieldError } from '../utils/apiErrors' 
import AppButton from '../components/ui/AppButton.vue'
import AppCard from '../components/ui/AppCard.vue'
import FieldError from '../components/ui/FieldError.vue'

const email = ref('')
const status = ref('')
const error = ref('')
const fieldErrors = ref({})
const loading = ref(false)

async function submit() {
    status.value = ''
    error.value = ''
    fieldErrors.value = {}
    loading.value = true

    try {
        const { data } = await api.post('/forgot-password', {
            email: email.value,
        })

        status.value = data.message
    } catch (err) {
        fieldErrors.value = getValidationErrors(err)
        error.value = getErrorMessage(err, 'Failed to send the reset link.')
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <section class="stack">
        <h1 class="page-title">Password Reset</h1>

        <AppCard>
            <form @submit.prevent="submit">
                <div class="field">
                    <label>Email</label>
                    <input v-model="email" type="email"/>
                    <FieldError :message="firstFieldError(fieldErrors, 'email')"/>
                </div>

                <div class="button-row">
                    <AppButton :disabled="loading" type="submit">
                        {{ loading ? 'Sending...' : 'Reset link sent.' }}
                    </AppButton>
                </div>
            </form>

            <p v-if="status">{{ status }}</p>
            <p v-if="error">{{ error }}</p>
        </AppCard>
    </section>
</template>
<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../lib/api'
import { getErrorMessage, getValidationErrors, firstFieldError } from '../utils/apiErrors' 
import AppButton from '../components/ui/AppButton.vue'
import AppCard from '../components/ui/AppCard.vue'
import FieldError from '../components/ui/FieldError.vue'

const route = useRoute()
const router = useRouter()

const token = ref(route.query.token || '')
const email = ref(route.query.email || '')
const password = ref('')
const passwordConfirmation = ref('')
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
        const { data } = await api.post('/reset-password', {
            token: token.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
        })

        status.value = data.message
        router.push('/login')
    } catch (err) {
        error.value =
        fieldErrors.value = getValidationErrors(err)
        error.value = getErrorMessage(err, 'Failed to reset password.')            
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <section class="stack">
        <h1 class="page-title">New Password</h1>
        
        <AppCard>
            <form @submit.prevent="submit">
                <div class="field">
                    <label>Email</label>
                    <input v-model="email" type="email"/>
                    <FieldError :message="firstFieldError(fieldErrors, 'email')"/>
                </div>

                <div class="field">
                    <label>New Password</label>
                    <input v-model="password" type="password"/>
                    <FieldError :message="firstFieldError(fieldErrors, 'password')"/>
                </div>

                <div class="field">
                    <label>New Password Again</label>
                    <input v-model="passwordConfirmation" type="password"/>
                </div>

                <div class="button-row">
                    <AppButton :disabled="loading" type="submit">
                        {{ loading ? 'Saving...' : 'Password saved' }}
                    </AppButton>
                </div>
            </form>

            <p v-if="status" class="status-text">{{ status }}</p>
            <p v-if="error" class="error-text">{{ error }}</p>
        </AppCard>
    </section>
</template>
<script setup>
import AppCard from '../components/ui/AppCard.vue'
</script>

<template>
    <section class="stack">
        <h1 class="page-title">404</h1>

        <AppCard>
            <p>Not found.</p>
            <RouterLink to="/login">Go back</RouterLink>
        </AppCard>
    </section>
</template>
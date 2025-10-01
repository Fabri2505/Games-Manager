<script setup lang="ts">
import { RouterView } from 'vue-router';
import PantallaCarga from './components/PantallaCarga.vue';
import { useLoading } from './composable/useLoading';
const { isLoading } = useLoading()

</script>

<template>
  <div id="app">
    <PantallaCarga v-if="isLoading" />
    <RouterView />
  </div>

</template>

<style scoped>

</style>

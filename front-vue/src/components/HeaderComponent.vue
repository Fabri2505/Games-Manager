<script setup lang="ts">
    defineProps<{
        titulo:string,
        descrip?:string
    }>()
</script>

<template>
  <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div class="flex items-center gap-2">
      <slot name="boton_return"></slot>
      <div>
        <h1 class="text-2xl font-bold">{{ titulo }}</h1>
        <p v-if="descrip" class="text-sm text-muted-foreground">{{ descrip }}</p>
      </div>    
    </div>
    <div class="flex items-center gap-2">
      <slot name="botones"></slot>
    </div>
    
  </header>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { Link, Plus } from 'lucide-vue-next';

const newGame = ref<boolean>(false);

const emit = defineEmits<{
  gameTypeChanged: [isNewGame: boolean];
}>();

const handleNewGame = () => {
  newGame.value = true;
  emit('gameTypeChanged', true);
};

const handleContinueSeries = () => {
  newGame.value = false;
  emit('gameTypeChanged', false);
};
</script>

<template>
  <div class="flex justify-center w-full">
    <div class="p-1 w-full bg-[#E3E3E3]">
      <button 
        @click="handleNewGame"
        :class="[
          'flex w-full gap-2 justify-center py-1 transition-colors',
          newGame ? 'bg-blue-500 text-white' : 'bg-white'
        ]"
      > 
        <Plus/> Nuevo Juego
      </button>
    </div>
    <div class="p-1 w-full bg-[#E3E3E3]">
      <button 
        @click="handleContinueSeries"
        :class="[
          'flex w-full gap-2 justify-center py-1 transition-colors',
          !newGame ? 'bg-blue-500 text-white' : 'bg-white'
        ]"
      > 
        <Link/> Continuar Serie
      </button>
    </div>
  </div>
</template>

<style scoped></style>

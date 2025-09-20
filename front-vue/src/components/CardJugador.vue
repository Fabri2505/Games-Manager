<template>
  <div 
    class="player-card" :class="{ 'selected': player.selected }" @click="seleccionarJugador">
    <div class="avatar">
      {{ iniciales }}
      <div v-if="player.selected" class="selected-icon">
        ✓
      </div>
    </div>
    <div class="player-info">
      <h3 class="player-name">{{ player.nombre }}</h3>
      <p class="player-email line-clamp-1">{{ player.email }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Player } from '@/utils/schema';
import { computed } from 'vue';


interface Props {
  player: Player;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  playerSelected: [player: { id: number, nombre: string }]
}>();

const iniciales = computed(()=>{
  if(!props.player.nombre) return '';

  const words = props.player.nombre.trim().split(' ');
  if (words.length === 1) {
    return words[0].charAt(0).toUpperCase();
  }
  
  return (words[0].charAt(0) + words[words.length - 1].charAt(0)).toUpperCase()
});

const seleccionarJugador = () => {
  emit('playerSelected', { id: props.player.id, nombre: props.player.nombre });
};

</script>

<style scoped>
.player-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.2s ease;
  width: 200px;
  height: 180px;
  text-align: center;
}

.player-card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Estilos para el estado seleccionado */
.player-card.selected {
  border: 2px solid #3b82f6;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  background-color: #f8faff;
}

.avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 24px;
  margin-bottom: 16px;
  flex-shrink: 0;
}

/* Avatar cuando está seleccionado */
.selected .avatar {
  border: 3px solid #3b82f6;
  transform: scale(1.05);
}

.selected-icon {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 20px;
  height: 20px;
  background-color: #10b981;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
  color: white;
  border: 2px solid white;
}

.player-info {
  flex: 1;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.player-name {
  margin: 0 0 8px 0;
  font-size: 16px;
  font-weight: 600;
  color: #1f2937;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -line-clamp: 2;
  -webkit-box-orient: vertical;
  line-height: 1.4;
}

/* Texto cuando está seleccionado */
.selected .player-name {
  color: #3b82f6;
}

.player-email {
  margin: 0;
  font-size: 13px;
  color: #6b7280;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -line-clamp: 2;
  -webkit-box-orient: vertical;
  line-height: 1.3;
}

/* Variaciones de colores para avatares */
.player-card:nth-child(2n) .avatar {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.player-card:nth-child(3n) .avatar {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.player-card:nth-child(4n) .avatar {
  background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.player-card:nth-child(5n) .avatar {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}
</style>
<template>
  <div class="rounded-lg bg-white shadow-sm border-0 p-6">
    <div class="flex items-center gap-2 mb-4">
      <component :is="icon" class="w-5 h-5" :class="iconColor" />
      <h3 class="text-lg font-semibold">{{ title }}</h3>
    </div>
    
    <div class="space-y-3">
      <template v-if="type === 'victories'">
        <!-- Distribución de Victorias con barras de progreso -->
        <div 
          v-for="player in players" 
          :key="player.id"
          class="flex items-center justify-between"
        >
          <span class="text-sm font-medium">{{ player.name }}</span>
          <div class="flex items-center gap-2 flex-1 mx-3">
            <div class="flex-1 bg-gray-200 rounded-full h-2">
              <div 
                class="h-2 rounded-full" 
                :class="getProgressBarColor(player.id)"
                :style="{ width: `${player.percentage}%` }"
              ></div>
            </div>
            <span class="text-sm font-medium">{{ player.value }}</span>
            <span class="text-xs text-gray-500">{{ player.percentage }}%</span>
          </div>
        </div>
      </template>

      <template v-else-if="type === 'balance'">
        <!-- Balance simple -->
        <div 
          v-for="player in players" 
          :key="player.id"
          class="flex items-center justify-between"
        >
          <span class="text-sm font-medium">{{ player.name }}</span>
          <span 
            class="text-sm font-semibold"
            :class="getBalanceColor(player.value)"
          >
            {{ formatBalance(player.value) }}
          </span>
        </div>
      </template>

      <template v-else>
        <!-- Tipo genérico -->
        <div 
          v-for="player in players" 
          :key="player.id"
          class="flex items-center justify-between"
        >
          <span class="text-sm font-medium">{{ player.name }}</span>
          <span class="text-sm font-medium">{{ player.value }}</span>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Component } from 'vue'

interface StatsPlayer {
  id: number
  name: string
  value: number
  percentage?: number
}

interface Props {
  title: string
  icon: Component
  iconColor?: string
  type: 'victories' | 'balance' | 'generic'
  players: StatsPlayer[]
}

const props = withDefaults(defineProps<Props>(), {
  iconColor: 'text-blue-500'
})

const progressBarColors = [
  'bg-blue-500',
  'bg-pink-500', 
  'bg-green-500',
  'bg-purple-500',
  'bg-orange-500',
  'bg-cyan-500',
  'bg-red-500',
  'bg-yellow-500'
]

const getProgressBarColor = (playerId: number): string => {
  return progressBarColors[playerId % progressBarColors.length]
}

const getBalanceColor = (value: number): string => {
  if (value > 0) return 'text-green-600'
  if (value < 0) return 'text-red-600'
  return 'text-gray-600'
}

const formatBalance = (value: number): string => {
  if (value > 0) return `+${value}`
  return value.toString()
}
</script>
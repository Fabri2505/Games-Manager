<script setup lang="ts">
import type { Component } from 'vue';

interface InfoItem {
  label: string;
  value: string;
  valueClass?: string;
}

interface Props {
  title: string;
  icon: Component;
  colorScheme?: 'blue' | 'green' | 'red' | 'yellow' | 'purple';
  items: InfoItem[];
}

defineProps<Props>();
</script>

<template>
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div 
                class="w-10 h-10 rounded-full flex items-center justify-center"
                :class="{
                    'bg-blue-100': colorScheme === 'blue',
                    'bg-green-100': colorScheme === 'green',
                    'bg-red-100': colorScheme === 'red',
                    'bg-yellow-100': colorScheme === 'yellow',
                    'bg-purple-100': colorScheme === 'purple'
                }"
            >
                <component 
                    :is="icon" 
                    class="w-5 h-5"
                    :class="{
                        'text-blue-600': colorScheme === 'blue',
                        'text-green-600': colorScheme === 'green',
                        'text-red-600': colorScheme === 'red',
                        'text-yellow-600': colorScheme === 'yellow',
                        'text-purple-600': colorScheme === 'purple'
                    }"
                />
            </div>
            <h4 class="font-semibold text-gray-800">{{ title }}</h4>
        </div>
        <div class="space-y-2">
            <div 
                v-for="(item, index) in items" 
                :key="index" 
                class="flex justify-between"
            >
                <span class="text-gray-600">{{ item.label }}:</span>
                <span 
                    class="font-medium"
                    :class="item.valueClass || ''"
                >
                    {{ item.value }}
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
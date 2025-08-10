<script setup lang="ts">
import { ArrowRight, Wallet2, Gamepad2, Trophy, Heart } from 'lucide-vue-next';

interface Props {
  title: string;
  description: string;
  route: string;
  isPopular?: boolean;
  color?: 'blue' | 'green' | 'purple' | 'orange' | 'red';
  icon?: 'wallet' | 'gamepad' | 'trophy' | 'heart';
}

const props = withDefaults(defineProps<Props>(), {
  isPopular: false,
  color: 'blue',
  icon: 'wallet'
});

const emit = defineEmits<{
  navigate: [route: string];
}>();

const colorClasses = {
  blue: {
    badge: 'bg-blue-100 text-blue-800 border-blue-200',
    button: 'hover:bg-blue-50 text-blue-700',
    title: 'group-hover:text-blue-700',
    icon: 'bg-blue-100 text-blue-800'
  },
  green: {
    badge: 'bg-green-100 text-green-800 border-green-200',
    button: 'hover:bg-green-50 text-green-700',
    title: 'group-hover:text-green-700',
    icon: 'bg-green-100 text-green-800'
  },
  purple: {
    badge: 'bg-purple-100 text-purple-800 border-purple-200',
    button: 'hover:bg-purple-50 text-purple-700',
    title: 'group-hover:text-purple-700',
    icon: 'bg-purple-100 text-purple-800'
  },
  orange: {
    badge: 'bg-orange-100 text-orange-800 border-orange-200',
    button: 'hover:bg-orange-50 text-orange-700',
    title: 'group-hover:text-orange-700',
    icon: 'bg-orange-100 text-orange-800'
  },
  red: {
    badge: 'bg-red-100 text-red-800 border-red-200',
    button: 'hover:bg-red-50 text-red-700',
    title: 'group-hover:text-red-700',
    icon: 'bg-red-100 text-red-800'
  }
};

const iconComponents = {
  wallet: Wallet2,
  gamepad: Gamepad2,
  trophy: Trophy,
  heart: Heart
};

const handleNavigate = () => {
  emit('navigate', props.route);
};
</script>

<template>
  <div class="group w-full max-w-sm rounded-lg p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow bg-white">
    <!-- Header with icon and popular badge -->
    <div class="flex items-start justify-between mb-4">
      <div :class="[
              'p-3 rounded-lg transition-transform duration-300 group-hover:scale-110',
              colorClasses[color].icon
            ]">
        <component 
          :is="iconComponents[icon]" 
          :size="50"
        />
      </div>
      <div 
        v-if="isPopular"
        :class="[
          'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium transition-colors',
          colorClasses[color].badge
        ]"
      >
        Popular
      </div>
    </div>
    
    <!-- Content -->
    <div class="mb-6">
      <h3 :class="['text-2xl font-bold text-gray-900 mb-2',colorClasses[color].title]">{{ title }}</h3>
      <p class="text-gray-600 leading-relaxed">{{ description }}</p>
    </div>
    
    <!-- Action button -->
    <button 
      @click="handleNavigate"
      :class="[
        'inline-flex items-center gap-2 px-4 py-2 rounded-md font-medium transition-colors w-full justify-center',
        colorClasses[color].button,
        'border border-gray-200'
      ]"
    >
      <span>Iniciar juego</span>
      <ArrowRight :size="18" />
    </button>
  </div>
</template>
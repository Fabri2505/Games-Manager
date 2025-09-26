<script setup lang="ts">
import { ArrowRight, Wallet2, Gamepad2, Trophy, Heart, Lock } from 'lucide-vue-next';

interface Props {
  title: string;
  description: string;
  route: string;
  isPopular?: boolean;
  disabled?: boolean;
  color?: 'blue' | 'green' | 'purple' | 'orange' | 'red';
  icon?: 'wallet' | 'gamepad' | 'trophy' | 'heart';
}

const props = withDefaults(defineProps<Props>(), {
  isPopular: false,
  disabled: false,
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

const disabledClasses = {
  badge: 'bg-gray-100 text-gray-500 border-gray-200',
  button: 'bg-gray-100 text-gray-500 cursor-not-allowed',
  title: 'text-gray-500',
  icon: 'bg-gray-100 text-gray-400'
};

const iconComponents = {
  wallet: Wallet2,
  gamepad: Gamepad2,
  trophy: Trophy,
  heart: Heart
};

const handleNavigate = () => {
  if (!props.disabled) {
    emit('navigate', props.route);
  }
};
</script>

<template>
  <div :class="[
    'group w-full max-w-sm rounded-lg p-6 border shadow-sm transition-shadow bg-white relative',
    disabled 
      ? 'border-gray-200 opacity-75' 
      : 'border-gray-200 hover:shadow-md'
  ]">
    
    <!-- Overlay para estado bloqueado -->
    <div 
      v-if="disabled"
      class="absolute inset-0 bg-gray-50 opacity-10 rounded-lg pointer-events-none"
    ></div>
    
    <!-- Header with icon and popular/disabled badge -->
    <div class="flex items-start justify-between mb-4">
      <div :class="[
        'p-3 rounded-lg transition-transform duration-300',
        disabled 
          ? disabledClasses.icon
          : [colorClasses[color].icon, 'group-hover:scale-110']
      ]">
        <component 
          :is="iconComponents[icon]" 
          :size="50"
        />
      </div>
      
      <!-- Badge dinámico -->
      <div 
        v-if="isPopular && !disabled"
        :class="[
          'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium transition-colors',
          colorClasses[color].badge
        ]"
      >
        Popular
      </div>
      
      <div 
        v-else-if="disabled"
        :class="[
          'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium gap-1',
          disabledClasses.badge
        ]"
      >
        <Lock :size="12" />
        Bloqueado
      </div>
    </div>
    
    <!-- Content -->
    <div class="mb-6">
      <h3 :class="[
        'text-2xl font-bold mb-2',
        disabled 
          ? disabledClasses.title
          : ['text-gray-900', colorClasses[color].title]
      ]">
        {{ title }}
      </h3>
      <p :class="[
        'leading-relaxed',
        disabled ? 'text-gray-400' : 'text-gray-600'
      ]">
        {{ description }}
      </p>
    </div>
    
    <!-- Action button -->
    <button 
      @click="handleNavigate"
      :disabled="disabled"
      :class="[
        'inline-flex items-center gap-2 px-4 py-2 rounded-md font-medium transition-colors w-full justify-center border',
        disabled 
          ? [disabledClasses.button, 'border-gray-200']
          : [colorClasses[color].button, 'border-gray-200']
      ]"
    >
      <Lock v-if="disabled" :size="18" />
      <span>{{ disabled ? 'Próximamente' : 'Iniciar juego' }}</span>
      <ArrowRight v-if="!disabled" :size="18" />
    </button>
  </div>
</template>
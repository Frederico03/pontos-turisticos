@props(['name' => 'nota', 'value' => 0])

<div x-data="{ rating: {{ old($name, $value) }} }" class="flex items-center gap-1">
    <input type="hidden" name="{{ $name }}" :value="rating">
    
    <template x-for="star in 5" :key="star">
        <button 
            type="button"
            @click="rating = star"
            @mouseenter="$el.parentElement.querySelectorAll('svg').forEach((s, i) => { s.style.fill = i < star ? 'currentColor' : 'none' })"
            @mouseleave="$el.parentElement.querySelectorAll('svg').forEach((s, i) => { s.style.fill = i < rating ? 'currentColor' : 'none' })"
            class="focus:outline-none transition-colors duration-150"
        >
            <svg 
                class="w-8 h-8 text-yellow-400 transition-all duration-150" 
                :style="star <= rating ? 'fill: currentColor' : 'fill: none'"
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
        </button>
    </template>
    
    <span x-show="rating > 0" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
        <span x-text="rating"></span> estrela<span x-show="rating > 1">s</span>
    </span>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        // Garantir que Alpine.js está disponível
    });
</script>

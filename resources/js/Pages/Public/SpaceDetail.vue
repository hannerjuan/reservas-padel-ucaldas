<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    space: Object,
    availableSlots: Array,
    selectedDate: String
});

const date = ref(props.selectedDate);

watch(date, (newDate) => {
    router.get(route('spaces.public.show', props.space.slug), { date: newDate }, { preserveState: true });
});
</script>

<template>
    <PublicLayout>
        <div class="py-12 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4">
                <Link :href="route('home')" class="text-blue-600 font-bold mb-4 inline-block">&larr; Volver</Link>
                
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h1 class="text-3xl font-black mb-2">{{ space.name }}</h1>
                    <p class="text-gray-500 mb-6 uppercase tracking-widest font-bold">{{ space.type }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-lg mb-2 border-b">Detalles técnicos</h3>
                            <p class="text-gray-600 mb-4">{{ space.description }}</p>
                            <div class="flex justify-between py-2 border-b text-sm">
                                <span>Capacidad</span>
                                <span class="font-bold">{{ space.capacity }} jugadores</span>
                            </div>
                            <div class="flex justify-between py-2 text-sm">
                                <span>Precio por hora</span>
                                <span class="font-bold text-green-600 text-lg">${{ space.price_per_hour }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="font-bold mb-4">Ver Disponibilidad</h3>
                            <input type="date" v-model="date" :min="new Date().toISOString().split('T')[0]" class="w-full mb-6 border-gray-300 rounded-md">
                            
                                                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="font-bold mb-4 text-gray-800">Ver Disponibilidad y Tarifas</h3>
                                <input type="date" v-model="date" :min="new Date().toISOString().split('T')[0]" class="w-full mb-6 border-gray-300 rounded-md shadow-sm">
                                
                                <div v-if="availableSlots.length > 0" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <Link v-for="slot in availableSlots" :key="slot.start" 
                                        :href="route('reservations.new', { space: space.slug, start: slot.start })"
                                        class="bg-white border border-gray-200 p-3 text-center rounded-xl shadow-sm hover:border-blue-600 hover:shadow-md transition flex flex-col items-center justify-center relative group">
                                        
                                        <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition">{{ slot.display }}</span>
                                        
                                        <span class="text-xs font-black text-green-600 mt-1">${{ slot.price.toLocaleString() }}</span>
                                        
                                        <span v-if="slot.label" class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider whitespace-nowrap shadow-sm">
                                            {{ slot.label }}
                                        </span>
                                    </Link>
                                </div>
                                <div v-else class="text-center py-8 text-gray-400 italic">
                                    No hay franjas disponibles para esta fecha.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
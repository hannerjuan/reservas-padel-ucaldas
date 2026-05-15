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
                            
                            <div v-if="availableSlots.length > 0" class="grid grid-cols-3 gap-2">
                                <Link v-for="slot in availableSlots" :key="slot.start" 
                                    :href="route('reservations.new', { space: space.slug, start: slot.start })"
                                    class="bg-white border border-blue-600 text-blue-600 py-2 text-center text-xs font-bold rounded hover:bg-blue-600 hover:text-white transition">
                                    {{ slot.display }}
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
    </PublicLayout>
</template>
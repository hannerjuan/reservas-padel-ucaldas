<script setup>
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    selectedSpace: Object,
    spaces: Array,
    currentDate: String,
    startOfWeek: String,
    endOfWeek: String,
    reservations: Array,
    blockedSlots: Array
});

// Función para cambiar de semana (suma o resta 7 días)
const changeWeek = (days) => {
    const date = new Date(props.currentDate);
    // Ajuste de zona horaria básico
    date.setDate(date.getDate() + days);
    const formattedDate = date.toISOString().split('T')[0];
    
    router.get(route('admin.calendar'), { 
        space_id: props.selectedSpace?.id, 
        date: formattedDate 
    }, { preserveState: true });
};

// Función para cambiar de cancha de pádel
const changeSpace = (event) => {
    router.get(route('admin.calendar'), { 
        space_id: event.target.value, 
        date: props.currentDate 
    }, { preserveState: true });
};

// Agrupar eventos (reservas y bloqueos) por día de la semana para pintarlos en la vista
const getEventsForDay = (dateString) => {
    const dayReservations = props.reservations.filter(r => r.start_time.startsWith(dateString));
    const dayBlocks = props.blockedSlots.filter(b => b.start_time.startsWith(dateString));
    
    return {
        reservations: dayReservations,
        blocks: dayBlocks
    };
};

// Generar los 7 días de la semana actual
const weekDays = computed(() => {
    let days = [];
    let current = new Date(props.startOfWeek);
    for (let i = 0; i < 7; i++) {
        days.push({
            date: current.toISOString().split('T')[0],
            name: current.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric', month: 'short' })
        });
        current.setDate(current.getDate() + 1);
    }
    return days;
});
</script>

<template>
    <AppLayout title="Calendario de Ocupación">

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                    <h1 class="text-2xl font-bold text-gray-800">Calendario de Ocupación</h1>
                    
                    <div>
                        <select @change="changeSpace" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option v-for="space in spaces" :key="space.id" :value="space.id" :selected="space.id === selectedSpace?.id">
                                {{ space.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button @click="changeWeek(-7)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                            &larr; Semana Anterior
                        </button>
                        <button @click="changeWeek(7)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                            Semana Siguiente &rarr;
                        </button>
                    </div>
                </div>

                <p class="text-gray-600 mb-6 text-center font-medium">
                    Mostrando semana del {{ startOfWeek }} al {{ endOfWeek }} para <span class="font-bold text-blue-600">{{ selectedSpace?.name }}</span>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                    <div v-for="day in weekDays" :key="day.date" class="border rounded-lg bg-gray-50 flex flex-col h-96">
                        <div class="bg-gray-800 text-white text-center py-2 font-bold capitalize rounded-t-lg">
                            {{ day.name }}
                        </div>
                        
                        <div class="p-2 flex-1 overflow-y-auto space-y-2">
                            
                            <div v-for="res in getEventsForDay(day.date).reservations" :key="'res-'+res.id" 
                                 :class="res.status === 'confirmada' ? 'bg-green-100 border-green-400 text-green-800' : 'bg-yellow-100 border-yellow-400 text-yellow-800'"
                                 class="border-l-4 p-2 rounded text-sm shadow-sm">
                                <div class="font-bold text-xs uppercase">{{ res.status }}</div>
                                <div>{{ new Date(res.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }} - {{ new Date(res.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</div>
                                <div class="truncate font-medium">{{ res.user_name }}</div>
                            </div>

                            <div v-for="block in getEventsForDay(day.date).blocks" :key="'blk-'+block.id" 
                                 class="bg-red-100 border-l-4 border-red-500 text-red-800 p-2 rounded text-sm shadow-sm">
                                <div class="font-bold text-xs uppercase">BLOQUEADO</div>
                                <div>{{ new Date(block.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }} - {{ new Date(block.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</div>
                                <div class="truncate text-xs">{{ block.reason || 'Mantenimiento' }}</div>
                            </div>

                            <div v-if="getEventsForDay(day.date).reservations.length === 0 && getEventsForDay(day.date).blocks.length === 0" class="text-center text-gray-400 text-xs mt-4">
                                Sin eventos
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </AppLayout>
</template>
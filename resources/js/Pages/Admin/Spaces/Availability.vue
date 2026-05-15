<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ space: Object, availabilities: Array });

const form = useForm({
    availabilities: props.availabilities
});

const dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

const submit = () => {
    form.put(route('availabilities.update', props.space.slug));
};
</script>

<template>
    <AppLayout title="Configurar Horarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Horarios Semanales: {{ space.name }}
            </h2>
        </template>

        <div class="py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white shadow-xl rounded-lg p-6">
                    <div class="space-y-4">
                        <div v-for="(day, index) in form.availabilities" :key="day.id" 
                            class="flex items-center justify-between p-4 border-b last:border-0">
                            
                            <div class="w-32 font-bold text-gray-700">{{ dayNames[index] }}</div>
                            
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="day.is_open" class="rounded text-blue-600">
                                    <span class="ml-2 text-sm">{{ day.is_open ? 'Abierto' : 'Cerrado' }}</span>
                                </label>

                                <div v-if="day.is_open" class="flex items-center space-x-2">
    <input type="time" v-model="day.start_time" class="border-gray-300 rounded-md text-sm">
    <span class="text-gray-400">a</span>
    <input type="time" v-model="day.end_time" class="border-gray-300 rounded-md text-sm">
</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" :disabled="form.processing" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
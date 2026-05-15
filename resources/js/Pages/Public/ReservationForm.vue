<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    space: Object,
    start_time: String,
    end_time: String,
    errors: Object
});

const form = useForm({
    space_id: props.space.id,
    start_time: props.start_time || '',
    end_time: props.end_time || '',
    user_name: '',
    user_email: '',
    notes: ''
});

const submit = () => {
    form.post(route('reservations.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('user_name', 'user_email', 'notes'),
    });
};
</script>

<template>
    <Head :title="'Reservar ' + space.name" />

    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <Link :href="route('spaces.public.show', space.slug)" class="text-blue-600 hover:text-blue-800 mb-4 inline-block font-semibold">
                &larr; Volver a la cancha
            </Link>

            <div class="bg-white rounded-lg shadow-xl overflow-hidden mt-4 p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Solicitar Reserva</h1>
                <p class="text-gray-600 mb-6">Estás reservando: <span class="font-bold">{{ space.name }}</span></p>

                <div v-if="errors.start_time" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ errors.start_time }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" v-model="form.start_time" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha y Hora de Fin</label>
                            <input type="datetime-local" v-model="form.end_time" required 
                            :min="form.start_time"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" v-model="form.user_name" required placeholder="Ej: Hanner Obando"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <span v-if="errors.user_name" class="text-red-500 text-xs">{{ errors.user_name }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" v-model="form.user_email" required placeholder="correo@ejemplo.com"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <span v-if="errors.user_email" class="text-red-500 text-xs">{{ errors.user_email }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notas adicionales (Opcional)</label>
                        <textarea v-model="form.notes" rows="3" placeholder="Ej: Necesitamos alquiler de palas..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" :disabled="form.processing"
                            class="bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow hover:bg-blue-700 transition disabled:opacity-50">
                            {{ form.processing ? 'Procesando...' : 'Confirmar Solicitud' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
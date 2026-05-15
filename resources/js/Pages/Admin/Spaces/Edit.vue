<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    space: Object
});

const form = useForm({
    name: props.space.name,
    type: props.space.type,
    capacity: props.space.capacity,
    description: props.space.description,
    price_per_hour: props.space.price_per_hour,
    is_active: props.space.is_active ? true : false
});

const submit = () => {
    // Al editar, en Inertia enviamos una petición PUT
    form.put(route('spaces.update', props.space.slug));
};
</script>

<template>
    <AppLayout title="Editar cancha" >

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <Link :href="route('spaces.index')" class="text-blue-600 hover:text-blue-800 mb-4 inline-block font-semibold">
                    &larr; Volver al listado
                </Link>

                <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Cancha: {{ space.name }}</h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre de la Cancha</label>
                            <input type="text" v-model="form.name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option>Pádel Techada</option>
                                <option>Pádel Descubierta</option>
                                <option>Pádel Panorámica</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Capacidad (Jugadores)</label>
                            <input type="number" v-model="form.capacity" required min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio por Hora ($)</label>
                            <input type="number" v-model="form.price_per_hour" required min="0" step="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea v-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" v-model="form.is_active" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Cancha Activa (Visible para el público)</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 transition">
                            Actualizar Cancha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
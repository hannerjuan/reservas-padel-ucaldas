<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, router } from '@inertiajs/vue3';

defineProps({ blockedSlots: Array, spaces: Array });

const form = useForm({
    space_id: '',
    start_time: '',
    end_time: '',
    reason: ''
});

const submit = () => {
    form.post(route('blocked-slots.store'), {
        onSuccess: () => form.reset()
    });
};

const removeBlock = (id) => {
    if (confirm('¿Eliminar este bloqueo y habilitar el horario?')) {
        router.delete(route('blocked-slots.destroy', id));
    }
};
</script>

<template>
    <AppLayout title="Bloqueos de Horario">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bloqueos por Mantenimiento / Eventos</h2>
        </template>

        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-lg shadow h-fit">
                    <h3 class="text-lg font-bold mb-4">Nuevo Bloqueo</h3>
                    
                    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-bold">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm font-bold">
                        {{ $page.props.flash.error }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Cancha</label>
                            <select v-model="form.space_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled>Seleccione una cancha...</option>
                                <option v-for="space in spaces" :key="space.id" :value="space.id">{{ space.name }}</option>
                            </select>
                            <div v-if="form.errors.space_id" class="text-red-500 text-xs mt-1">{{ form.errors.space_id }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Desde</label>
                            <input type="datetime-local" v-model="form.start_time" class="w-full border-gray-300 rounded-md shadow-sm">
                            <div v-if="form.errors.start_time" class="text-red-500 text-xs mt-1">{{ form.errors.start_time }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Hasta</label>
                            <input type="datetime-local" v-model="form.end_time" class="w-full border-gray-300 rounded-md shadow-sm">
                            <div v-if="form.errors.end_time" class="text-red-500 text-xs mt-1">{{ form.errors.end_time }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Motivo</label>
                            <input type="text" v-model="form.reason" placeholder="Ejem: Mantenimiento de luces" class="w-full border-gray-300 rounded-md shadow-sm">
                            <div v-if="form.errors.reason" class="text-red-500 text-xs mt-1">{{ form.errors.reason }}</div>
                        </div>
                        <button type="submit" :disabled="form.processing" class="w-full bg-red-600 text-white font-bold py-2 rounded hover:bg-red-700 disabled:opacity-50 transition">
                            Bloquear Horario
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Bloqueos Registrados</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 uppercase font-bold text-gray-600">
                                <tr>
                                    <th class="p-3 text-left">Cancha</th>
                                    <th class="p-3 text-left">Periodo</th>
                                    <th class="p-3 text-left">Motivo</th>
                                    <th class="p-3 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="blockedSlots.length === 0">
                                    <td colspan="4" class="p-4 text-center text-gray-500 italic">No hay bloqueos activos.</td>
                                </tr>
                                <tr v-for="block in blockedSlots" :key="block.id" class="border-t hover:bg-gray-50">
                                    <td class="p-3 font-medium">{{ block.space.name }}</td>
                                    <td class="p-3">
                                        <div class="text-gray-800">{{ new Date(block.start_time).toLocaleString([], {dateStyle: 'short', timeStyle: 'short'}) }}</div>
                                        <div class="text-gray-500 text-xs">hasta {{ new Date(block.end_time).toLocaleString([], {dateStyle: 'short', timeStyle: 'short'}) }}</div>
                                    </td>
                                    <td class="p-3">{{ block.reason }}</td>
                                    <td class="p-3 text-center">
                                        <button @click="removeBlock(block.id)" class="bg-gray-200 text-red-600 px-3 py-1 rounded text-xs font-bold hover:bg-red-100 transition">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
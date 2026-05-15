<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    spaces: Array
});
</script>

<template>
    <Head title="Canchas Disponibles" />

    <PublicLayout>
        <div class="bg-gray-800 py-20 text-center px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.padeladdict.com/wp-content/uploads/2021/04/pista-padel.jpg')] bg-cover bg-center"></div>
            <div class="relative z-10 max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                    Eleva tu nivel de juego
                </h1>
                <p class="text-lg text-gray-300 mb-8">
                    Reserva tu cancha en segundos y asegura tu partido. Instalaciones premium abiertas de lunes a domingo.
                </p>
            </div>
        </div>

        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div v-for="(space, index) in spaces" :key="space.id" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col transform hover:-translate-y-1">
                        
                        <div class="relative h-56 w-full group overflow-hidden">
                            <img :src="[
                                'https://images.unsplash.com/photo-1646649853703-7645147474ba?q=80&w=871&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                                'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                                'https://images.unsplash.com/photo-1709587824751-dd30420f5cf3?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
                            ][index % 3]" :alt="space.name" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" />
                            
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-sm">
                                <span class="text-blue-800 text-xs font-bold uppercase tracking-wide">{{ space.type }}</span>
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ space.name }}</h2>
                                <p class="text-gray-600 mb-6 h-12 line-clamp-2">{{ space.description }}</p>
                            </div>
                            
                            <div class="flex justify-between items-end mb-6 pt-4 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Valor por hora</p>
                                    <p class="text-3xl font-black text-green-600">${{ space.price_per_hour }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center text-gray-500 bg-gray-100 px-2 py-1 rounded text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ space.capacity }} px
                                    </div>
                                </div>
                            </div>

                            <Link :href="route('spaces.public.show', space.slug)" class="block w-full bg-gray-900 text-white text-center font-bold py-3 rounded-lg hover:bg-blue-600 transition duration-300 shadow-md">
                                Ver Disponibilidad
                            </Link>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
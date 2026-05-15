<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50 font-sans text-gray-900">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <div class="max-w-7xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="text-center text-sm font-bold tracking-wide">
                    🎉 ¡Gran inauguración! Usa el código <span class="bg-white text-blue-700 px-2 py-0.5 rounded ml-1">PADEL2026</span> para un 20% de descuento en reservas nocturnas.
                </div>
            </div>
        </div>

        <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('home')" class="flex items-center gap-2 group">
                            <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-black text-xl group-hover:bg-blue-700 transition">
                                P
                            </div>
                            <span class="font-extrabold text-xl tracking-tight text-gray-800">Pádel<span class="text-blue-600">Pro</span></span>
                        </Link>
                    </div>

                    <div class="flex items-center space-x-6">
                        <Link :href="route('home')" class="text-gray-600 hover:text-blue-600 font-medium transition">
                            Canchas
                        </Link>
                        
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('admin.home')" class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-full font-bold shadow-md transition transform hover:scale-105">
                                Mi Panel Admin
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 font-bold px-4 py-2 border border-blue-600 rounded-full hover:bg-blue-50 transition">
                                Iniciar Sesión
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <slot />
        </main>

        <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="font-extrabold text-2xl tracking-tight text-white mb-4 block">Pádel<span class="text-blue-500">Pro</span></span>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        El mejor complejo deportivo para disfrutar del pádel. Instalaciones de primer nivel, torneos y el mejor ambiente para ti y tus amigos.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-sm">
                        <li><Link :href="route('home')" class="hover:text-blue-400 transition">Ver Canchas</Link></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Reglamento del Club</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Atención al Cliente</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📞 +57 (300) 123-4567</li>
                        <li>✉️ reservas@padelpro.com</li>
                        <li>📍 Manizales, Caldas, Colombia</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-sm text-center text-gray-500">
                &copy; {{ new Date().getFullYear() }} PádelPro. Todos los derechos reservados. Desarrollo por Hanner Obando.
            </div>
        </footer>

    </div>
</template>
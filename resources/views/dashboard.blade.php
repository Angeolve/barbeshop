<x-app-layout>
    <div class="py-12 flex items-center justify-center min-h-[70vh]">
        <div class="max-w-4xl w-full mx-auto sm:px-6 lg:px-8 text-center">
            <!-- Glowing Ring & Greeting -->
            <div class="inline-flex items-center justify-center p-0.5 mb-8 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 shadow-xl shadow-orange-500/5">
                <div class="px-6 py-2 rounded-full bg-white">
                    <span class="text-xs font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500 uppercase">
                        SISTEMA DE RESERVAS
                    </span>
                </div>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none mb-6">
                Bienvenido, <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 via-yellow-500 to-orange-500">{{ Auth::user()->name }}</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 font-medium max-w-2xl mx-auto mb-10 leading-relaxed">
                Gestiona tus citas, clientes, barberos y servicios desde el menú lateral. Disfruta de una experiencia minimalista y de primer nivel.
            </p>

            <!-- Small luxury details / barber poles styling -->
            <div class="flex items-center justify-center gap-4 text-amber-500/40">
                <span class="w-8 h-px bg-gradient-to-r from-transparent to-amber-500/40"></span>
                <span class="text-lg">💈</span>
                <span class="w-8 h-px bg-gradient-to-l from-transparent to-amber-500/40"></span>
            </div>
        </div>
    </div>
</x-app-layout>

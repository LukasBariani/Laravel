<x-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Formulário de criação de Chirp -->
        @auth
            @include('chirps.partials.create-form')
        @else
            <div class="card bg-base-100 shadow-xl mb-8">
                <div class="card-body text-center">
                    <p class="mb-4">Faça login para compartilhar seus pensamentos!</p>
                    <a href="/login" class="btn btn-primary">Entrar</a>
                </div>
            </div>
        @endauth
        
        <!-- Lista de Chirps -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold mb-4">📝 Últimos Chirps</h2>
            
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body text-center">
                        <p class="text-base-content/60">Nenhum chirp por aqui ainda...</p>
                        @auth
                            <p class="text-sm mt-2">Seja o primeiro a chirpar! 🐦</p>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
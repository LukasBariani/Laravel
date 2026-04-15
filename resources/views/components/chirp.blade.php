@props(['chirp'])

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2">
                    <!-- Avatar -->
                    <div class="avatar">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs">
                            {{ substr($chirp->user->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <span class="font-semibold">{{ $chirp->user->name ?? 'Anonymous' }}</span>
                        <span class="text-xs text-gray-500 ml-2">{{ $chirp->created_at->diffForHumans() }}</span>
                        @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                            <span class="text-xs text-gray-400 italic ml-1">(editado)</span>
                        @endif
                    </div>
                    </div>
                    </div>
                    
                    @can('update', $chirp)
                        <div class="flex gap-2">
                            <a href="{{ route('chirps.edit', $chirp) }}" class="btn btn-xs btn-ghost">Editar</a>
                            <form method="POST" action="{{ route('chirps.destroy', $chirp) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-ghost text-error" onclick="return confirm('Tem certeza?')">
                                    Excluir
                                </button>
                            </form>
                                </div>
                    @endcan
            </div>
        <p class="mt-3">{{ $chirp->message }}</p>
        
        <!-- Mostrar imagem se existir -->
        @if($chirp->image)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $chirp->image) }}" class="rounded-lg max-h-64 object-cover">
                </div>
        @endif
        
        <!-- Mostrar áudio se existir -->
        @if($chirp->audio)
            <div class="mt-3">
                <audio controls class="w-full">
                    <source src="{{ asset('storage/' . $chirp->audio) }}">
                </audio>
                        </div>
        @endif
    </div>
</div>
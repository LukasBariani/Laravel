@props(['chirp'])

@if($chirp->image)
    <div class="mt-3">
        <img src="{{ $chirp->image_url }}" 
             alt="Imagem do chirp"
             class="rounded-lg max-w-full max-h-96 object-contain cursor-pointer"
             onclick="openModal(this.src)"
             loading="lazy">
    </div>
@endif

@if($chirp->audio)
    <div class="mt-3 p-3 bg-base-200 rounded-lg">
        <audio controls class="w-full">
            <source src="{{ $chirp->audio_url }}" type="audio/mpeg">
            Seu navegador não suporta áudio.
        </audio>
    </div>
@endif

@if($chirp->link_url)
    <div class="mt-3">
        <a href="{{ $chirp->link_url }}" target="_blank" rel="noopener noreferrer" class="block">
            <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                @if($chirp->link_image)
                    <div class="h-48 overflow-hidden">
                        <img src="{{ $chirp->link_image }}" 
                             alt="{{ $chirp->link_title }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif
                <div class="p-3">
                    <div class="text-xs text-primary font-semibold mb-1">
                        {{ parse_url($chirp->link_url, PHP_URL_HOST) }}
                    </div>
                    <div class="font-semibold text-sm mb-1 line-clamp-2">
                        {{ $chirp->link_title }}
                    </div>
                    @if($chirp->link_description)
                        <div class="text-xs text-base-content/70 line-clamp-2">
                            {{ $chirp->link_description }}
                        </div>
                    @endif
                </div>
            </div>
        </a>
    </div>
@endif

<!-- Modal para visualizar imagem -->
<script>
function openModal(src) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center cursor-pointer';
    modal.onclick = function() { this.remove(); };
    
    const img = document.createElement('img');
    img.src = src;
    img.className = 'max-w-[90vw] max-h-[90vh] object-contain';
    
    modal.appendChild(img);
    document.body.appendChild(modal);
}
</script>
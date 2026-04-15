<div class="card bg-base-100 shadow-xl mb-8">
    <div class="card-body">
        <h2 class="card-title text-xl mb-2">O que está acontecendo?</h2>
        
        <form method="POST" action="{{ route('chirps.store') }}" enctype="multipart/form-data" id="chirp-form">
            @csrf
        
            <!-- Campo de texto -->
            <div class="form-control">
                <textarea name="message" id="message" class="textarea textarea-bordered w-full focus:textarea-primary" rows="3"
                    placeholder="Digite seu chirp... (suporta imagens, áudio e links automáticos)">{{ old('message') }}</textarea>
                <div class="flex justify-between items-center mt-1">
                    <span class="text-xs text-base-content/50">
                        <span id="char-count">0</span>/500 caracteres
                    </span>
                    @error('message')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                    </div>
                    </div>
            <!-- Preview de link detectado -->
            <div id="link-preview" class="hidden mt-3"></div>
            
            <!-- Mídias -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- Upload de imagem -->
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="label-text">Imagem (opcional)</span>
                    </label>
                    <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        class="file-input file-input-bordered file-input-sm" onchange="previewImage(this)">
                    <div id="image-preview" class="mt-2 hidden relative">
                        <img class="h-32 rounded-lg object-cover" alt="Preview">
                        <button type="button" onclick="removeImage()"
                            class="absolute top-1 right-1 btn btn-circle btn-xs bg-error text-white">
                            ✕
                        </button>
                    </div>
                    <span class="text-xs text-base-content/50 mt-1">Formatos: JPG, PNG, GIF, WEBP (Max 5MB)</span>
                    @error('image')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                    </div>
                    
                    <!-- Upload de áudio -->
                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                            <span class="label-text">Áudio (opcional)</span>
                        </label>
                        <input type="file" name="audio" id="audio-input" accept="audio/mpeg,audio/wav,audio/ogg,audio/m4a"
                            class="file-input file-input-bordered file-input-sm" onchange="previewAudio(this)">
                        <div id="audio-preview" class="mt-2 hidden">
                            <div class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                                <span id="audio-name" class="text-sm flex-1"></span>
                                <button type="button" onclick="removeAudio()" class="btn btn-ghost btn-xs text-error">
                                    Remover
                                </button>
                            </div>
                        </div>
                        <span class="text-xs text-base-content/50 mt-1">Formatos: MP3, WAV, OGG, M4A (Max 10MB)</span>
                        @error('audio')
                            <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    </div>
                    
                    <!-- Botões de ação -->
                    <div class="card-actions justify-between items-center mt-4">
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('image-input').click()" class="btn btn-ghost btn-sm">
                                📷 Imagem
                            </button>
                            <button type="button" onclick="document.getElementById('audio-input').click()" class="btn btn-ghost btn-sm">
                                🎵 Áudio
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Chirpar
                        </button>
                    </div>
        </form>
        </div>
</div>

<script>
    // Contador de caracteres
    const messageInput = document.getElementById('message');
    const charCount = document.getElementById('char-count');

    messageInput.addEventListener('input', function () {
        charCount.textContent = this.value.length;
        if (this.value.length > 500) {
            this.classList.add('textarea-error');
            charCount.classList.add('text-error');
        } else {
            this.classList.remove('textarea-error');
            charCount.classList.remove('text-error');
        }

        // Detectar links
        detectLinks(this.value);
    });

    // Contador inicial
    charCount.textContent = messageInput.value.length;

    // Preview de imagem
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = preview.querySelector('img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const imageInput = document.getElementById('image-input');
        const preview = document.getElementById('image-preview');
        imageInput.value = '';
        preview.classList.add('hidden');
        preview.querySelector('img').src = '';
    }

    // Preview de áudio
    function previewAudio(input) {
        const preview = document.getElementById('audio-preview');
        const audioName = document.getElementById('audio-name');

        if (input.files && input.files[0]) {
            audioName.textContent = input.files[0].name;
            preview.classList.remove('hidden');
        }
    }

    function removeAudio() {
        const audioInput = document.getElementById('audio-input');
        const preview = document.getElementById('audio-preview');
        audioInput.value = '';
        preview.classList.add('hidden');
        audioName.textContent = '';
    }

    // Detectar links automaticamente
    async function detectLinks(text) {
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        const urls = text.match(urlRegex);

        const linkPreview = document.getElementById('link-preview');

        if (urls && urls.length > 0) {
            const url = urls[0];
            try {
                // Mostrar loading
                linkPreview.innerHTML = `
                <div class="border rounded-lg p-3 bg-base-200 animate-pulse">
                    <div class="h-4 bg-base-300 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-base-300 rounded w-1/2"></div>
                </div>
            `;
            linkPreview.classList.remove('hidden');

            // Tentar buscar metadados do link via API
            const response = await fetch(`https://api.microlink.io?url=${encodeURIComponent(url)}`);
            const data = await response.json();
            
            if (data.data) {
                linkPreview.innerHTML = `
                    <a href="${url}" target="_blank" rel="noopener noreferrer" class="block">
                        <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            ${data.data.image ? `
                                <div class="h-40 overflow-hidden">
                                    <img src="${data.data.image.url}" alt="${data.data.title}" class="w-full h-full object-cover">
                                </div>
                            ` : ''}
                            <div class="p-3">
                                <div class="text-xs text-primary font-semibold mb-1">
                                    ${new URL(url).hostname}
                                </div>
                                <div class="font-semibold text-sm mb-1 line-clamp-2">
                                    ${data.data.title || url}
                                </div>
                                ${data.data.description ? `
                                    <div class="text-xs text-base-content/70 line-clamp-2">
                                        ${data.data.description}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </a>
                    <button type="button" onclick="removeLinkPreview()" class="btn btn-ghost btn-xs mt-2">Remover preview</button>
                `;
            }
        } catch (error) {
            console.error('Erro ao buscar preview:', error);
            linkPreview.innerHTML = `
                <div class="border rounded-lg p-3 bg-base-200">
                    <div class="text-sm">🔗 Link detectado: <a href="${url}" target="_blank" class="text-primary">${url}</a></div>
                </div>
            `;
                        }
                    } else {
                        linkPreview.classList.add('hidden');
                        linkPreview.innerHTML = '';
                    }
                }

                function removeLinkPreview() {
                    document.getElementById('link-preview').classList.add('hidden');
                }

                // Validar tamanhos de arquivo antes de enviar
                document.getElementById('chirp-form').addEventListener('submit', function (e) {
                    const imageFile = document.getElementById('image-input').files[0];
                    const audioFile = document.getElementById('audio-input').files[0];

                    if (imageFile && imageFile.size > 5 * 1024 * 1024) {
                        e.preventDefault();
                        alert('A imagem não pode exceder 5MB!');
                        return false;
                    }

                    if (audioFile && audioFile.size > 10 * 1024 * 1024) {
                        e.preventDefault();
                        alert('O áudio não pode exceder 10MB!');
                        return false;
                    }
                });
            </script>
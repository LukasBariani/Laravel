<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">✏️ Editar Chirp</h2>
                
                <form method="POST" action="/chirps/{{ $chirp->id }}" enctype="multipart/form-data" id="edit-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Campo de texto -->
                    <div class="form-control">
                        <textarea name="message" id="message" class="textarea textarea-bordered w-full focus:textarea-primary" rows="4"
                                  required>{{ old('message', $chirp->message) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-base-content/50">
                                <span id="char-count">{{ strlen($chirp->message) }}</span>/500 caracteres
                            </span>
                            @error('message')
                                <span class="text-error text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Mídia atual -->
                    @if($chirp->image)
                        <div class="form-control mt-4">
                            <label class="cursor-pointer label justify-start gap-2">
                                <input type="checkbox" name="remove_image" class="checkbox checkbox-primary checkbox-sm">
                                <span class="label-text">Remover imagem atual</span>
                            </label>
                            <div class="mt-2">
                                <img src="{{ $chirp->image_url }}" class="h-40 rounded-lg object-cover" alt="Imagem atual">
                            </div>
                        </div>
                    @endif
                    
                    @if($chirp->audio)
                        <div class="form-control mt-4">
                            <label class="cursor-pointer label justify-start gap-2">
                                <input type="checkbox" name="remove_audio" class="checkbox checkbox-primary checkbox-sm">
                                <span class="label-text">Remover áudio atual</span>
                            </label>
                            <div class="mt-2 p-3 bg-base-200 rounded-lg">
                                <audio controls class="w-full">
                                    <source src="{{ $chirp->audio_url }}">
                                </audio>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Adicionar nova mídia -->
                    <div class="divider text-sm">Adicionar nova mídia</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <!-- Upload de nova imagem -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">📷 Nova imagem (opcional)</span>
                            </label>
                            <input type="file" 
                                   name="image" 
                                   accept="image/*"
                                   class="file-input file-input-bordered file-input-sm"
                                   onchange="previewNewImage(this)">
                            <div id="new-image-preview" class="mt-2 hidden">
                                <img class="h-32 rounded-lg object-cover" alt="Preview">
                            </div>
                            @error('image')
                                <span class="text-error text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Upload de novo áudio -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">🎵 Novo áudio (opcional)</span>
                            </label>
                            <input type="file" name="audio" accept="audio/*" class="file-input file-input-bordered file-input-sm"
                                onchange="previewNewAudio(this)">
                            <div id="new-audio-preview" class="mt-2 hidden">
                                <div class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                    <span id="new-audio-name" class="text-sm flex-1"></span>
                                </div>
                            </div>
                            @error('audio')
                                <span class="text-error text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Botões -->
                    <div class="card-actions justify-end mt-6">
                        <a href="/" class="btn btn-ghost">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
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
        });

        function previewNewImage(input) {
            const preview = document.getElementById('new-image-preview');
            const previewImg = preview.querySelector('img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }

        function previewNewAudio(input) {
            const preview = document.getElementById('new-audio-preview');
            const audioName = document.getElementById('new-audio-name');

            if (input.files && input.files[0]) {
                audioName.textContent = input.files[0].name;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
                audioName.textContent = '';
            }
        }
    </script>
</x-layout>
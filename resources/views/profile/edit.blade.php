<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">Editar Perfil</h2>

                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text">Foto de Perfil</span>
                        </label>

                        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-start">
                            <!-- Preview do avatar -->
                            <div class="avatar">
                                <div
                                    class="w-24 h-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" id="avatar-preview">
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <input type="file" name="avatar" id="avatar" accept="image/*"
                                    class="file-input file-input-bordered file-input-primary w-full max-w-xs"
                                    onchange="previewAvatar(this)">
                                <div class="text-xs text-gray-500">
                                    Formatos permitidos: JPG, PNG, GIF (Max. 2MB)
                                </div>
                                <!-- Botão de remover foto removido -->
                            </div>
                        </div>
                        @error('avatar')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="divider"></div>

                    <!-- Nome -->
                    <div class="form-control mb-4">
                        <label class="label" for="name">
                            <span class="label-text">Nome</span>
                        </label>
                        <input type="text" name="name" id="name"
                            class="input input-bordered w-full @error('name') input-error @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control mb-4">
                        <label class="label" for="email">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" name="email" id="email"
                            class="input input-bordered w-full @error('email') input-error @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="divider">Alterar Senha (opcional)</div>

                    <!-- Senha atual -->
                    <div class="form-control mb-4">
                        <label class="label" for="current_password">
                            <span class="label-text">Senha Atual</span>
                        </label>
                        <input type="password" name="current_password" id="current_password"
                            class="input input-bordered w-full @error('current_password') input-error @enderror">
                        @error('current_password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                        <label class="label">
                            <span class="label-text-alt text-info">Necessário apenas se quiser alterar a senha</span>
                        </label>
                    </div>

                    <!-- Nova senha -->
                    <div class="form-control mb-4">
                        <label class="label" for="password">
                            <span class="label-text">Nova Senha</span>
                        </label>
                        <input type="password" name="password" id="password"
                            class="input input-bordered w-full @error('password') input-error @enderror">
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Confirmar nova senha -->
                    <div class="form-control mb-6">
                        <label class="label" for="password_confirmation">
                            <span class="label-text">Confirmar Nova Senha</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="input input-bordered w-full">
                    </div>

                    <div class="card-actions justify-end">
                        <a href="/" class="btn btn-ghost">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layout>
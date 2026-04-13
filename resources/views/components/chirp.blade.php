@props(['chirp'])

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <div class="flex items-center gap-4">
            @if($chirp->user)
                <div class="w-8 h-8 rounded-full overflow-hidden">
                    <img 
                        src="https://avatars.laravel.cloud/{{ urlencode($chirp->user->name) }}"
                        alt="{{ $chirp->user->name }}"
                        class="w-full h-full object-cover"
                    />
                </div>
            @else
                <div class="w-8 h-8 rounded-full overflow-hidden">
                    <img 
                        src="https://avatars.laravel.cloud/f61123d5-0b27-434c-a4ae-c653c7fc9ed6?vibe=stealth"
                        alt="Anonymous User"
                        class="w-full h-full object-cover"
                    />
                </div>
            @endif

            <div class="min-w-0">
                <div class="flex items-center gap-1">
                    <span class="text-sm font-semibold">
                        {{ $chirp->user->name ?? 'Anonymous' }}
                    </span>
                    <span class="text-base-content/60">·</span>
                    <span class="text-sm text-base-content/60">
                        {{ $chirp->created_at->diffForHumans() }}
                    </span>
                </div>

                <p class="mt-1 text-sm break-all w-full">
                    {{ $chirp->message }}
                </p>
            </div>
        </div>
    </div>
</div>
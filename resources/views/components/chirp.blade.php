@props(['chirp'])

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <div class="flex items-start gap-4">
            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-semibold truncate">
                            {{ $chirp->user->name ?? 'Anonymous' }}
                        </span>
                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60 whitespace-nowrap">
                            {{ $chirp->created_at->diffForHumans() }}
                            @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                                <span class="text-base-content/60">·</span>
                                <span class="text-sm text-base-content/60 italic">edited</span>
                            @endif
                        </span>
                    </div>
                    
                    @can('update', $chirp)
                        <div class="flex gap-1">
                            <a href="/chirps/{{ $chirp->id }}/edit" class="btn btn-ghost btn-xs">
                                Edit
                            </a>
                            <form method="POST" action="/chirps/{{ $chirp->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this chirp?')"
                                    class="btn btn-ghost btn-xs text-error">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>

                <p class="mt-1 text-sm"
                    style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; white-space: normal;">
                    {{ $chirp->message }}
                </p>
            </div>
        </div>
    </div>
</div>
<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Embed\Embed;

class ChirpController extends Controller
{
    public function index()
    {
        $chirps = Chirp::with('user')->latest()->get();
        return view('chirps.index', compact('chirps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240', // 10MB
        ]);

        $chirp = new Chirp();
        $chirp->message = $validated['message'];
        $chirp->user_id = auth()->id();

        // Processar imagem
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chirps/images', 'public');
            $chirp->image = $path;
        }

        // Processar áudio
        if ($request->hasFile('audio')) {
            $path = $request->file('audio')->store('chirps/audio', 'public');
            $chirp->audio = $path;
        }

        // Processar links (extrair metadados)
        if ($request->filled('message')) {
            $this->extractLinks($request->message, $chirp);
        }

        $chirp->save();

        return redirect()->route('chirps.index')
            ->with('success', 'Chirp publicado com sucesso!');
    }

    public function edit(Chirp $chirp)
    {
        Gate::authorize('update', $chirp);
        return view('chirps.edit', compact('chirp'));
    }

    public function update(Request $request, Chirp $chirp)
    {
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
            'remove_image' => 'nullable|boolean',
            'remove_audio' => 'nullable|boolean',
        ]);

        $chirp->message = $validated['message'];

        // Remover imagem
        if ($request->has('remove_image') && $chirp->image) {
            Storage::disk('public')->delete($chirp->image);
            $chirp->image = null;
        }

        // Remover áudio
        if ($request->has('remove_audio') && $chirp->audio) {
            Storage::disk('public')->delete($chirp->audio);
            $chirp->audio = null;
        }

        // Upload nova imagem
        if ($request->hasFile('image')) {
            if ($chirp->image) {
                Storage::disk('public')->delete($chirp->image);
            }
            $path = $request->file('image')->store('chirps/images', 'public');
            $chirp->image = $path;
        }

        // Upload novo áudio
        if ($request->hasFile('audio')) {
            if ($chirp->audio) {
                Storage::disk('public')->delete($chirp->audio);
            }
            $path = $request->file('audio')->store('chirps/audio', 'public');
            $chirp->audio = $path;
        }

        // Processar links novamente
        $this->extractLinks($request->message, $chirp);

        $chirp->save();

        return redirect()->route('chirps.index')
            ->with('success', 'Chirp atualizado com sucesso!');
    }

    public function destroy(Chirp $chirp)
    {
        Gate::authorize('delete', $chirp);

        // Remover arquivos
        if ($chirp->image) {
            Storage::disk('public')->delete($chirp->image);
        }
        if ($chirp->audio) {
            Storage::disk('public')->delete($chirp->audio);
        }

        $chirp->delete();

        return redirect()->route('chirps.index')
            ->with('success', 'Chirp excluído com sucesso!');
    }

    // Método para extrair links da mensagem
    private function extractLinks($message, &$chirp)
    {
        // Limpar dados de link anteriores
        $chirp->link_url = null;
        $chirp->link_title = null;
        $chirp->link_description = null;
        $chirp->link_image = null;

        // Regex para encontrar URLs
        $pattern = '/https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)/';

        if (preg_match($pattern, $message, $matches)) {
            $url = $matches[0];

            try {
                // Usar embed para extrair metadados (requer instalação)
                $embed = new Embed();
                $info = $embed->get($url);

                $chirp->link_url = $url;
                $chirp->link_title = $info->title ?? null;
                $chirp->link_description = $info->description ?? null;
                $chirp->link_image = $info->image ?? null;

            } catch (\Exception $e) {
                // Fallback simples
                $chirp->link_url = $url;
                $chirp->link_title = $url;
            }
        }
    }
}
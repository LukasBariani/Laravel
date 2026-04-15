<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validação dos dados
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
            'current_password' => ['required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Atualizar nome e email
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Processar upload do avatar
        if ($request->hasFile('avatar')) {
            // Deletar avatar antigo se existir
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Salvar novo avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }
        
        // Atualizar senha se foi fornecida
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('profile.edit')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
    
    // Método para remover avatar
    public function removeAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }
        
        return redirect()->route('profile.edit')
            ->with('success', 'Foto removida com sucesso!');
    }
}
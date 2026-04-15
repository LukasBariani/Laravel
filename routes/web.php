<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// Rota principal
Route::get('/', [ChirpController::class, 'index'])->name('chirps.index');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    // Rotas do Chirp
    Route::post('/chirps', [ChirpController::class, 'store'])->name('chirps.store');
    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit'])->name('chirps.edit');
    Route::put('/chirps/{chirp}', [ChirpController::class, 'update'])->name('chirps.update');
    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy'])->name('chirps.destroy');

    // Rotas do Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/')->with('success', 'Logout realizado com sucesso!');
    })->name('logout');
});

// Rotas de Login (inline)
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/')->with('success', 'Login realizado com sucesso!');
    }

    return back()->withErrors([
        'email' => 'As credenciais informadas não correspondem aos nossos registros.',
    ])->onlyInput('email');
})->middleware('guest')->name('login.authenticate');

// Rotas de Registro (inline)
Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/')->with('success', 'Conta criada com sucesso!');
})->middleware('guest');
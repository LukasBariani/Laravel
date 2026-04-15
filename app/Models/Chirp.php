<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chirp extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'image',
        'audio',
        'link_url',
        'link_title',
        'link_description',
        'link_image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor para imagem
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // Accessor para áudio
    public function getAudioUrlAttribute()
    {
        return $this->audio ? asset('storage/' . $this->audio) : null;
    }

    // Verificar se tem mídia
    public function hasMedia()
    {
        return $this->image || $this->audio || $this->link_url;
    }
}
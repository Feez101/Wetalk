<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    protected $fillable = ['community_id', 'name', 'slug', 'description', 'kind', 'is_private'];
    protected function casts(): array { return ['is_private' => 'boolean']; }
    public function community() { return $this->belongsTo(Community::class); }
    public function messages() { return $this->hasMany(Message::class); }
}

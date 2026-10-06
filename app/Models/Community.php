<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'type', 'description', 'owner_id', 'invite_code'];
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function members() { return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps(); }
    public function channels() { return $this->hasMany(Channel::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['channel_id', 'sender_id', 'recipient_id', 'body', 'translated_body', 'message_type', 'reply_to_id'];
    public function channel() { return $this->belongsTo(Channel::class); }
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function recipient() { return $this->belongsTo(User::class, 'recipient_id'); }
    public function replyTo() { return $this->belongsTo(self::class, 'reply_to_id'); }
}

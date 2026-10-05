<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Channel $channel) { return $channel->messages()->with('sender:id,name,username')->latest()->paginate(50); }
    public function store(Request $request)
    {
        $data = $request->validate(['channel_id' => 'nullable|exists:channels,id', 'recipient_id' => 'nullable|exists:users,id', 'body' => 'required|string|max:5000', 'message_type' => 'nullable|in:text,announcement,file,voice', 'reply_to_id' => 'nullable|exists:messages,id']);
        abort_if(empty($data['channel_id']) && empty($data['recipient_id']), 422, 'Choose a channel or recipient.');
        return response()->json(Message::create($data + ['sender_id' => $request->user()->id, 'message_type' => $data['message_type'] ?? 'text']), 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    public function store(Request $request, Community $community)
    {
        abort_unless($community->members()->whereKey($request->user()->id)->exists(), 403);
        $data = $request->validate(['name' => 'required|string|max:60', 'description' => 'nullable|string|max:300', 'kind' => 'nullable|in:text,announcement,event', 'is_private' => 'nullable|boolean']);
        return response()->json($community->channels()->create($data + ['slug' => Str::slug($data['name']), 'kind' => $data['kind'] ?? 'text']), 201);
    }
}

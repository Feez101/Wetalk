<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(Request $request) { return $request->user()->communities()->withCount('members')->get(); }
    public function show(Community $community) { return $community->load(['channels', 'members:id,name,username,preferred_language']); }
    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:80', 'type' => 'required|string|max:30', 'description' => 'nullable|string|max:500']);
        $community = Community::create($data + ['slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(5)), 'owner_id' => $request->user()->id, 'invite_code' => Str::upper(Str::random(10))]);
        $community->members()->attach($request->user()->id, ['role' => 'owner']);
        $community->channels()->create(['name' => 'general', 'slug' => 'general', 'description' => 'General discussion for everyone.', 'kind' => 'text']);
        return response()->json($community->load('channels'), 201);
    }
    public function join(Request $request)
    {
        $data = $request->validate(['invite_code' => 'required|string']);
        $community = Community::where('invite_code', strtoupper($data['invite_code']))->firstOrFail();
        $community->members()->syncWithoutDetaching([$request->user()->id => ['role' => 'member']]);
        return $community->load('channels');
    }
}

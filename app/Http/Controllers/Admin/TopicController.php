<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;

use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::orderBy('name')->paginate(10);
        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        return view('admin.topics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Topic::create([
            'name' => $request->name,
            'target_group' => $request->target_group,
            'description' => $request->description,
            'sources' => $request->sources,
        ]);

        return redirect()->route('admin.topics.index')->with('success', 'Téma vytvořeno.');
    }

    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $topic->update($request->only('name', 'target_group', 'description', 'sources'));

        return redirect()->route('admin.topics.index')->with('success', 'Téma upraveno.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('admin.topics.index')->with('success', 'Téma smazáno.');
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicPublicController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Topic::class);

        $topics = Topic::visibleFor($request->user())
            ->orderBy('name')
            ->paginate(10);

        return view('topics.index', compact('topics'));
    }

    public function show(Request $request, Topic $topic)
    {
        $this->authorize('view', $topic);

        $campaigns = $topic->campaigns()
            ->visibleFor($request->user())
            ->get();

        return view('topics.show', compact('topic', 'campaigns'));
    }
}
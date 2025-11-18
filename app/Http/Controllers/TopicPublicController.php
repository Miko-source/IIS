<?php

namespace App\Http\Controllers;

use App\Models\Topic;

class TopicPublicController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }
}

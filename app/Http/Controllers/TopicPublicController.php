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
    $campaigns = $topic->campaigns()->get(); // Načtení kampaní

    return view('topics.show', compact('topic', 'campaigns'));
}

}

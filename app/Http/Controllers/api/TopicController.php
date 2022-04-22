<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\topic\StoreTopicDraftRequest;
use App\Http\Requests\topic\StoreTopicRequest;
use App\Models\Topic;
use App\Models\TopicDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function storeDraft(StoreTopicDraftRequest $request)
    {
        $topicID = $request->input('id');
        if (empty($topicID)) {
            $description = TopicDescription::create([
                'content' => $request->input('description'),
            ]);
            $createdTopic = Topic::create([
                'user_id' => Auth::id(),
                'topic_section_id' => $request->input('topic_section_id'),
                'name' => $request->input('name'),
                'topic_description_id' => $description->id,
                'is_draft' => 1,
            ]);
            $foundTopic = Topic::find($createdTopic->id);
            $foundTopic = tap($foundTopic)->update([
                'slug' => Str::of($createdTopic->name)->snake() . '_' . $createdTopic->id,
            ]);
            return customResponse()
                ->data($foundTopic)
                ->message('You have successfully created drafted topic.')
                ->success()
                ->generate();
        }
        $topic = Topic::find($topicID);
        if (!$topic->is_draft) {
            return customResponse()
                ->data(null)
                ->message('You cant save a draft topic if already posted.')
                ->failed()
                ->generate();
        }
        $topic = tap($topic)->update([
            'user_id' => Auth::id(),
            'topic_section_id' => $request->input('topic_section_id'),
            'name' => $request->input('name'),
            'is_draft' => 1,
        ]);
        $topic->description->update([
            'content' => $request->input('description'),
        ]);
        return customResponse()
            ->data($topic)
            ->message('You have successfully updated drafted topic.')
            ->success()
            ->generate();
    }

    public function getDrafts()
    {
        $topics = Topic::with(['description'])
            ->where([
                'user_id' => Auth::id(),
                'is_draft' => 1,
            ])
            ->get();
        return customResponse()
            ->data($topics)
            ->message('You have successfully get drafted topics.')
            ->success()
            ->generate();
    }

    public function deleteDraft($topicID)
    {
        $topic = Topic::find($topicID)->delete();
        return customResponse()
            ->data($topic)
            ->message('You have successfully deleted a drafted topic.')
            ->success()
            ->generate();
    }

    public function store(StoreTopicRequest $request)
    {
        $topicID = $request->input('id');
        if (!empty($topicID)) {
            $topic = Topic::find($topicID);
            $topic = tap($topic)->update([
                'user_id' => Auth::id(),
                'id' => $topicID,
                'topic_section_id' => $request->input('topic_section_id'),
                'name' => $request->input('name'),
                'is_draft' => 0,
            ]);
            $topic->description->update([
                'content' => $request->input('description'),
            ]);
            return customResponse()
                ->data($topic)
                ->message('You have successfully posted a topic.')
                ->success()
                ->generate();
        }
        $description = TopicDescription::create([
            'content' => $request->input('description'),
        ]);
        $topic = Topic::create([
            'user_id' => Auth::id(),
            'topic_section_id' => $request->input('topic_section_id'),
            'name' => $request->input('name'),
            'topic_description_id' => $description->id,
            'is_draft' => 0,
        ]);
        $topic = tap($topic)->update([
            'slug' => Str::of($topic->name)->snake() . '_' . $topic->id,
        ]);
        return customResponse()
            ->data($topic)
            ->message('You have successfully posted a topic.')
            ->success()
            ->generate();
    }
}

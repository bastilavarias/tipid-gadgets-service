<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\topic\StoreTopicDraftRequest;
use App\Models\Topic;
use App\Models\TopicDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function storeDraft(StoreTopicDraftRequest $request)
    {
        if (empty($request->input('id'))) {
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

        $itemID = $request->input('id');
        $foundTopic = Topic::find($itemID);
        if (!$foundTopic->is_draft) {
            return customResponse()
                ->data(null)
                ->message('You cant save a draft topic if already posted.')
                ->failed()
                ->generate();
        }
        $foundTopic = tap($foundTopic)->update([
            'user_id' => Auth::id(),
            'topic_section_id' => $request->input('topic_section_id'),
            'name' => $request->input('name'),
            'is_draft' => 1,
        ]);
        $foundTopic->description->update([
            'content' => $request->input('description'),
        ]);
        return customResponse()
            ->data($foundTopic)
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
}

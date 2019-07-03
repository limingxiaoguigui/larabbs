<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use App\Http\Requests\Api\TopicRequest;

class TopicsController extends Controller
{
    //发布话题

    public  function store(TopicRequest  $request, Topic  $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())->setStatusCode(201);
    }
    //更新话题
    public  function  update(TopicRequest $request,  Topic  $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return  $this->response->item($topic, new TopicTransformer());
    }
}
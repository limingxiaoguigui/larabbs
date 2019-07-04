<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Reply;
use App\Transformers\ReplyTransformer;
use App\Http\Requests\Api\ReplyRequest;
use App\Models\User;

class RepliesController extends Controller
{
    //发表回复
    public function  store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic()->associate($topic);
        $reply->user()->associate($this->user());
        $reply->save();

        return  $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }
    //删除回复
    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic_id != $topic->id) {

            return  $this->response->errorBadRequest();
        }
        $this->authorize('destroy', $reply);
        $reply->delete();
        return  $this->response->noContent();
    }

    //话题回复列表
    public  function index(Topic $topic, Request $request)
    {
        app(\Dingo\Api\Transformer\Factory::class)->disableEagerLoading();
        $replies = $topic->replies()->paginate(20);
        if ($request->include) {
            $replies->load(explode(',', $request->include));
        }
        return  $this->response->paginator($replies, new  ReplyTransformer());
    }
    //某个用户的回复列表
    public  function  userIndex(User $user, Request  $request)
    {
        app(\Dingo\Api\Transformer\Factory::class)->disableEagerLoading();
        $replies = $user->replies()->paginate(20);

        if ($request->include) {


            $replies->load(explode(',', $request->include));
        }


        return  $this->response->paginator($replies, new ReplyTransformer());
    }
}
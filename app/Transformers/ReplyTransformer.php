<?php

namespace  App\Transformers;

use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class  ReplyTransformer  extends  TransformerAbstract
{
    public  function transform(Reply $reply)
    {
        return [
            'id' => $reply->id,
            'user_id' => $reply->user_id,
            'topic_id' => $reply->topic_id,
            'content' => $reply->content,
            'created_at' => (string) $reply->created_at,
            'updated_at' => (string) $reply->updated_at,

        ];
    }
}
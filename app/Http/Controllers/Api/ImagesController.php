<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Handlers\ImageUploadHandler;
use App\Transformers\ImageTransformer;
use App\Http\Requests\Api\ImageRequest;

class ImagesController extends Controller
{
    //上传图片
    public  function store(ImageRequest  $request, ImageUploadHandler $uploader, Image $image)
    {
        //获取当前用户
        $user = $this->user();
        $size = $request->type == 'avatar' ? 362 : 1024;
        $result = $uploader->save($request->image, str_plural($request->type), $user->id, $size);
        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image, new  ImageTransformer())->setStatusCode(201);
    }
}
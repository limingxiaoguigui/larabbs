<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Transformers\Linktransformer;

class LinksController extends Controller
{
    //获取资源列表
    public  function index(Link $link)
    {

        $links = $link->getAllCached();

        return $this->response->collection($links, new LinkTransformer());
    }
}
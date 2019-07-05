<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\PermissionTransformer;

class PermissionsController extends Controller
{
    //获取当前用户的权限列表
    public  function index()
    {
        $permissions = $this->user()->getAllPermissions();

        return  $this->response->collection($permissions,  new PermissionTransformer());
    }
}
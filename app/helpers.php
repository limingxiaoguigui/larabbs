<?php

//返回路由名称
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
//判断是否是某个路由
function  category_nav_active($category_id)
{
    return  active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}
//制造摘要
function make_excerpt($value, $lenght = 200)
{
    $exceprt = trim(preg_replace('/\r\n|\r|\n+/', '', strip_tags($value)));

    return str_limit($exceprt, $lenght);
}
function  model_admin_link($title, $model)
{
    return  model_link($title, $model, 'admin');
}
function  model_link($title, $model, $prefix = '')
{
    //获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);
    //初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';
    //使用站点的URL拼接全量url
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;
    //拼接HTML 返回
    return  '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    //从实体中获取完整的类名
    $full_class_name = get_class($model);
    //获取基础类名，列如
    $class_name = class_basename($full_class_name);
    //蛇形命名
    $snake_case_name = snake_case($class_name);

    //获取子串的复数形式
    return  str_plural($snake_case_name);
}
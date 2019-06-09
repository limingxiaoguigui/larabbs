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
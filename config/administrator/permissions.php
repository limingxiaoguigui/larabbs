<?php
use Spatie\Permission\Models\permission;


return  [

    'title' => '权限',
    'single' => '权限',
    'model' => Permission::class,
    'permission' => function () {
        return  Auth::user()->can('manage_users');
    },
    //对CRud动作的单独权限控制，通过返回的布尔值来控制权限
    'action_permissions' => [
        //控制新建按钮的显示
        'create' => function ($model) {

            return  true;
        },
        //允许更新
        'update' => function ($model) {
            return false;
        },
        //不允许删除
        'delete' => function ($model) {
            return  false;
        },
        'view' => function ($model) {
            return true;
        },
    ],
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'name' => [
            'title' => '标示'
        ],
        'operation' => [
            'title' => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'name' => [
            'title' => '标示(请慎重修改)',
            //表单条目标题胖的提示信息
            'hint' => '修改权限标识会影响代码的调用，请不要轻易更改'
        ],
        'roles' => [
            'type' => 'relationship',
            'title' => '角色',
            'name_field' => 'name',
        ],
    ],
    'filter' => [
        'name' => [
            'title' => '标示'
        ]
    ],
];
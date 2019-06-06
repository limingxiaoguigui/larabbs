<?php
namespace  App\Handlers;

use  Image;

class  ImageUploadHandler
{
    //只允许以下后缀的图片上传
    protected   $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    //上传方法
    public  function save($file, $folder, $file_prefix, $max_width = false)
    {
        //构建储存的文件夹规则，uploads/images/avatars/201905/21
        //文件夹切割能让查找效率更高
        $folder_name = "uploads/images/$folder/"  . date('Ym/d', time());
        //文件具体存储的物理路径,''public_path('')，获取的是public文件夹的物理路径。
        //值如：/home/vagrant/code/larabbs/public/uploads/images/avatar/201905/21/
        $upload_path = public_path() . '/' . $folder_name;
        //获取文件的后缀名，因为图片从剪切板里黏贴的后缀名为空，所以此处确保后缀一直存着
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        //拼接文件名，加前缀是为了增加辨析度，前缀乐意是相关数据模型的Id
        //值如：1_46446633_4445.png
        $file_name = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;
        //如果上传的不是图片将终止操作
        if (!in_array($extension, $this->allowed_ext)) {

            return  false;
        }
        //将图片移到我们的目标从储存路径中
        $file->move($upload_path, $file_name);
        //如果想限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {
            //次类中的封装函数用于裁剪图片
            $this->reduceSize($upload_path . '/' . $file_name, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$file_name"
        ];
    }

    //图片裁剪
    public  function  reduceSize($file_path, $max_width)
    {
        //先实例化，传参文件的磁盘物理路径
        $image = Image::make($file_path);
        //进行大小进行调整
        $image->resize($max_width, null, function ($constraint) {
            //设定宽度是$max_width,高度等比例双方缩放
            $constraint->aspectRatio();
            //防止裁图时图片尺寸变大
            $constraint->upsize();
        });
        $image->save();
    }
}
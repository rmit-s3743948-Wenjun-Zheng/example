<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

//标准api基类
class BaseController extends Controller
{
    //生成api方法
    protected function create($data, $msg='', $code=200):Response
    {
        //返回api结果
        $result = [
            //状态码
            'code' => $code,
            //返回api中的信息
            'msg' => $msg,
            //返回的数据
            'data' => $data
        ];

        return response($result);
    }

}

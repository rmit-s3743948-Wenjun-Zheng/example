<?php

//api请求的资源不存在的返回格式
$result = [
    //状态码
    'code' => 404,
    //返回api中的信息
    'msg' => 'Resource do not exist',
    //返回的数据
    'data' => []
];
header('Content-Type: application/json');
echo json_encode($result);

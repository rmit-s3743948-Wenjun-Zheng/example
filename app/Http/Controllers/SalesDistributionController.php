<?php

namespace App\Http\Controllers;

use App\Models\api\SalesDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesDistributionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证获取的数据
        $validator = Validator::make($request->all(), [
            //起始日期是合法日期，不为空
            'dataFrom' => 'required|date',
            //终止日期是合法日期，不为空，必须在终止日期之后
            'dataTo' => 'required|date|after:dataFrom',
        ]);

        //验证数据并提示信息
        if ($validator->fails()) {
            //如果数据有误，返回400状态码并返回错误信息
            return $this->create([], $validator->errors(), 400);
        }
        else
        {
            //如果正确进行查询
            //获取具体的值
            $tenantId = $request->input('tenantID');
            $dataFrom = $request->input('dataFrom');
            $dataTo = $request->input('dataTo');

            //获得查询的结果
            $saledistribution = new SalesDistribution();
            $result =  $saledistribution ->getSalesDistribution($tenantId, $dataFrom, $dataTo);

            //验证结果
            if (count($result) == 0)
            {
                //如果为空， 返回状态204并提示找不到数据
                return $this->create($result, 'no data match', 204);
            }
            else {
                //如果不为空，返回状态200并提示成功找到了数据,然后返回查询结果
                return $this->create(["salesOfAccounts" => $result], 'succeed to fetch invoice amount distributio', 200);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

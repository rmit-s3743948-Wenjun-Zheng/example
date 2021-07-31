<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesTrend extends Model
{
    use HasFactory;

    //查询 monthly invoice amount的方法
    public function getSalesTrend($tenantId, $dataFrom, $dataTo)
    {
        //判断tenantId是否为空
        if ($tenantId == null)
        {
            //如果为空，删除where中判断tenantId的语句
            return DB::table('invoices')
                ->select(DB::raw('sum( sub_total ) AS amount'),
                    DB::raw("date_format( create_time, '%Y-%m' ) AS yearMonth"))
                ->whereBetween('create_time', [$dataFrom, $dataTo])
                ->Where(function ($query) {
                    $query->where('STATUS', 'AUTHORISED')
                        ->orwhere('STATUS', 'PAID');
                })
                ->groupBy(DB::raw("date_format( create_time, '%Y-%m' )"))
                ->get();
        }
        else
        {
            //如果不为空，在where中加入判断tenantId的语句
            return DB::table('invoices')
                ->select(DB::raw('sum( sub_total ) AS amount'),
                    DB::raw("date_format( create_time, '%Y-%m' ) AS yearMonth"))
                ->whereBetween('create_time', [$dataFrom, $dataTo])
                ->where('tenant_id', $tenantId)
                ->Where(function ($query) {
                    $query->where('STATUS', 'AUTHORISED')
                        ->orwhere('STATUS', 'PAID');
                })
                ->groupBy(DB::raw("date_format( create_time, '%Y-%m' )"))
                ->get();
        }
    }
}



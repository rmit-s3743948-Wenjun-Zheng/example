<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesDistribution extends Model
{
    use HasFactory;

    public function getSalesDistribution($tenantId, $dataFrom, $dataTo)
    {
        //根据line_items表里的account code属性计算在指定时间段内并invoices状态是'AUTHORISED'和'PAID'的总amount
        $subtable = DB::table('line_items')
            ->join('invoices' , 'invoices.invoice_id','=','line_items.invoice_id')
            ->select('line_items.account_code', DB::raw('sum( line_items.line_amount ) AS amount '))
            ->whereBetween('line_items.create_time', [$dataFrom, $dataTo])
            ->Where(function ($query) {
                $query->where('invoices.STATUS', 'AUTHORISED')
                    ->orwhere('invoices.STATUS', 'PAID');
            })
            ->groupBy('line_items.account_code');

        //判断tenantId是否为空
        if ($tenantId == null)
        {
            //如果为空, 则不填加where条件判断，然后在根据之前query的结果($subtable)形成最终结果并返回
            $queryresult = DB::table('accounts')
                ->select('accounts.CODE','sub.amount', 'accounts.account_id','accounts.NAME')
                ->joinSub( $subtable, 'sub', function ($join){
                    $join->on('accounts.CODE', '=', 'sub.account_code');
                })
                ->orderByDesc(DB::raw('sub.amount'))
                ->get();

            return $queryresult;
        }
        else
        {
            //如果不为空, 则填加where条件判断，然后在根据之前query的结果($subtable)形成最终结果并返回
            $queryresult = DB::table('accounts')
                ->select('accounts.CODE','sub.amount', 'accounts.account_id','accounts.NAME')
                ->joinSub( $subtable, 'sub', function ($join){
                    $join->on('accounts.CODE', '=', 'sub.account_code');
                })
                ->where('accounts.tenant_id', $tenantId)
                ->orderByDesc(DB::raw('sub.amount'))
                ->get();

            return $queryresult;
        }
    }

}

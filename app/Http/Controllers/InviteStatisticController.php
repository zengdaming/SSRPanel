<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\ReferralLog;
use App\Http\Models\User;
use Response;
use Log;
use DB;

/**
* 邀请注册统计分析控制器
*/

class InviteStatisticController extends Controller
{
    
    /**
    * 管理员专用，邀请统计分析的总数据
    */
    public function index ( Request $request )
    {
        // $view['list'] = ReferralLog::query()->with(['user','refUser'])->paginate(15);

        $refList = User::selectRaw('count(*) as count, referral.id as uid,referral.username')
            ->leftJoin('user as referral','user.referral_uid','=','referral.id')
            // ->leftJoin('referral_log as log','referral.id','=','ref_user_id')
            ->groupBy('user.referral_uid')
            ->having('count','>',0)
            ->having('user.referral_uid','>',0)
            ->get();
        foreach ($refList as &$ref) {
            $t = ReferralLog::where('ref_user_id',$ref->uid)->sum('amount');
            $ref['totalAmount'] = $t;
        }
        $view['refList'] = $refList;
  
        return Response::view('admin/InviteStatistic', $view);
    }

    public function list ( Request $request  ){
        $refList = User::selectRaw('count(*) as count, referral.id as uid,referral.username')
            ->leftJoin('user as referral','user.referral_uid','=','referral.id')
            // ->leftJoin('referral_log as log','referral.id','=','ref_user_id')
            ->groupBy('user.referral_uid')
            ->having('count','>',0)
            ->having('user.referral_uid','>',0)
            ->get();
        foreach ($refList as &$ref) {
            $t = ReferralLog::where('ref_user_id',$ref->uid)->sum('amount');
            $ref['totalAmount'] = $t;
        }

        return Response::json(['status' => 'success', 'data' => ['refList' => $refList], 'message' => '成功']);
    }

    /**
    * 查看某个推广人的佣金交易记录.
    * ajax模式，返回json格式的数据
    */
    public function tradeLog ( Request $request )
    {
        $uid = $request->get('uid');//推广人的ID
        if( $uid <1 ){
            return Response::json(['status' => 'fail', 'data' => '', 'message' => '用户名已存在，请重新输入']);
        }

        $tradeLogList = ReferralLog::with(['user'])->where('ref_user_id',$uid)->get();

        return Response::json(['status' => 'success', 'data' => ['tradeLogList' => $tradeLogList], 'message' => '成功']);
    }
}
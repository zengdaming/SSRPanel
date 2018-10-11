<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use App\Http\Models\UserScoreLog;
use Illuminate\Http\Request;
use Response;
use Redirect;
use Captcha;
use Session;
use Cache;
use Cookie;

/**
 * 登录控制器，已经升级为Restful
 * Class LoginController
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    // 登录页
    public function index(Request $request)
    {
        if ($request->method() == 'POST') {
            $username = trim($request->get('username'));
            $password = trim($request->get('password'));
            $captcha = trim($request->get('captcha'));

            if (empty($username) || empty($password)) {
                Session::flash('errorMsg', '请输入用户名和密码');

                // return Redirect::back();
                return Response::json(['status' => 'fail', 'message' => '请输入用户名和密码']);
            }

            // 是否校验验证码
            if ($this->systemConfig['is_captcha']) {
                if (!Captcha::check($captcha)) {
                    Session::flash('errorMsg', '验证码错误，请重新输入');

                    // return Redirect::back()->withInput();
                    return Response::json(['status' => 'fail', 'message' => '验证码错误']);
                }
            }

            $user = User::query()->where('username', $username)->where('password', md5($password))->first();
            if (!$user) {
                Session::flash('errorMsg', '用户名或密码错误');

                return Redirect::back()->withInput();
            } else if (!$user->is_admin && $user->status < 0) {
                Session::flash('errorMsg', '账号已禁用');

                return Redirect::back();
            } else if ($user->status == 0 && $this->systemConfig['is_active_register'] && $user->is_admin == 0) {
                Session::flash('errorMsg', '账号未激活，请先<a href="/activeUser?username=' . $user->username . '" target="_blank"><span style="color:#000">【激活账号】</span></a>');

                return Redirect::back()->withInput();
            }

            // 更新登录信息
            $remember_token = "";
            User::query()->where('id', $user->id)->update(['last_login' => time()]);
            if ($request->get('remember')) {
                $remember_token = makeRandStr(20);

                User::query()->where('id', $user->id)->update(['last_login' => time(), "remember_token" => $remember_token]);
            } else {
                User::query()->where('id', $user->id)->update(['last_login' => time()]);
            }

            $userIp = $request->getClientIp();
            \Log::info('用户登陆IP：'.$userIp);


            // 登录送积分
            // if ($this->systemConfig['login_add_score']) {
            //     if (!Cache::has('loginAddScore_' . md5($username))) {
            //         $score = mt_rand($this->systemConfig['min_rand_score'], $this->systemConfig['max_rand_score']);
            //         $ret = User::query()->where('id', $user->id)->increment('score', $score);
            //         if ($ret) {
            //             $this->addUserScoreLog($user->id, $user->score, $user->score + $score, $score, '登录送积分');

            //             // 登录多久后再登录可以获取积分
            //             $ttl = $this->systemConfig['login_add_score_range'] ? $this->systemConfig['login_add_score_range'] : 1440;
            //             Cache::put('loginAddScore_' . md5($username), '1', $ttl);

            //             Session::flash('successMsg', '欢迎回来，系统自动赠送您 ' . $score . ' 积分，您可以用它兑换流量包');
            //         }
            //     }
            // }

            // 重新取出用户信息
            $userInfo = User::query()->where('id', $user->id)->first();

            Session::put('user', $userInfo->toArray());

            // 根据权限跳转
            if ($user->is_admin) {
                // return Redirect::to('admin')->cookie('remember', $remember_token, 36000);
                return Response::json(['status' => 'success', 'data' => ['jump' => 'admin'], 'message' => '成功']);
            }

            // return Redirect::to('user')->cookie('remember', $remember_token, 36000);
            return Response::json(['status' => 'success', 'data' => ['jump' => 'user'], 'message' => '成功']);
        } else {
            if ($request->cookie("remember")) {
                // 被禁用的用户不能自动登陆
                $u = User::query()->where('status', '>=', 0)->where("remember_token", $request->cookie("remember"))->first();
                if ($u) {
                    Session::put('user', $u->toArray());

                    if ($u->is_admin) {
                        // return Redirect::to('admin');
                        return Response::json(['status' => 'success', 'data' => ['jump' => 'admin'], 'message' => '成功']);
                    }

                    // return Redirect::to('user');
                    return Response::json(['status' => 'success', 'data' => ['jump' => 'user'], 'message' => '成功']);
                }
            }

            $view['is_captcha'] = $this->systemConfig['is_captcha'];
            $view['is_register'] = $this->systemConfig['is_register'];
            $view['is_invite_register'] = $this->systemConfig['is_invite_register'];
            $view['website_home_logo'] = $this->systemConfig['website_home_logo'];
            $view['website_analytics'] = $this->systemConfig['website_analytics'];
            $view['website_customer_service'] = $this->systemConfig['website_customer_service'];

            // 将推广参数存在cookie中，有效期7天
            $aff = intval($request->get('aff', 0));
            if($aff >0){
                Cookie::queue('register_aff',$aff,10080);//过期时间7天：第三个参数是过期时间，单位是分钟
            }

            return Response::view('login', $view);
        }
    }

    // 退出
    public function logout(Request $request)
    {
        Session::flush();

        return Redirect::to('login')->cookie('remember', "", 36000);
    }

}

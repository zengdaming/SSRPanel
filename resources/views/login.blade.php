<!DOCTYPE html>
<!--[if IE 8]> <html lang="{{app()->getLocale()}}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="{{app()->getLocale()}}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{app()->getLocale()}}">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title>{{trans('login.title')}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    {{-- <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" /> --}}
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    {{-- <link href="https://unpkg.com/element-ui@2.4.8/lib/theme-chalk/index.css" rel="stylesheet" type="text/css" /> --}}
    <link href="/assets/pages/css/login-1.min.css?v=3" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" />
    <style type="text/css">
        .my-title{
            font-size  : 2.5rem;
            text-align : center;
            margin-bottom: 20px;
        }
    </style>

</head>

<body class=" login">
<!-- BEGIN LOGO -->
<div class="logo">
    {{-- @if($website_home_logo)
        <a href="javascript:;"> <img src="{{$website_home_logo}}" alt="" style="width:270px; height:48px;"/> </a>
    @else
        <a href="javascript:;"> <img src="/assets/images/home_logo.png" alt="" /> </a>
    @endif --}}
    {{-- <H1 style="color: #FFF">平头哥网络</H1> --}}
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content" id="app">
    {{-- <nav style="padding-bottom: 20px;text-align: center;">
        @if(app()->getLocale() == 'zh-CN')
            <a href="{{url('lang', ['locale' => 'zh-tw'])}}">繁體中文</a>
            <a href="{{url('lang', ['locale' => 'en'])}}">English</a>
            <a href="{{url('lang', ['locale' => 'ja'])}}">日本語</a>
            <a href="{{url('lang', ['locale' => 'ko'])}}">한국어</a>
        @elseif(app()->getLocale() == 'zh-tw')
            <a href="{{url('lang', ['locale' => 'zh-CN'])}}">简体中文</a>
            <a href="{{url('lang', ['locale' => 'en'])}}">English</a>
            <a href="{{url('lang', ['locale' => 'ja'])}}">日本語</a>
            <a href="{{url('lang', ['locale' => 'ko'])}}">한국어</a>
        @elseif(app()->getLocale() == 'en')
            <a href="{{url('lang', ['locale' => 'zh-CN'])}}">简体中文</a>
            <a href="{{url('lang', ['locale' => 'zh-tw'])}}">繁體中文</a>
            <a href="{{url('lang', ['locale' => 'ja'])}}">日本語</a>
            <a href="{{url('lang', ['locale' => 'ko'])}}">한국어</a>
        @elseif(app()->getLocale() == 'ko')
            <a href="{{url('lang', ['locale' => 'zh-CN'])}}">简体中文</a>
            <a href="{{url('lang', ['locale' => 'zh-tw'])}}">繁體中文</a>
            <a href="{{url('lang', ['locale' => 'en'])}}">English</a>
            <a href="{{url('lang', ['locale' => 'ja'])}}">日本語</a>
        @elseif(app()->getLocale() == 'ja')
            <a href="{{url('lang', ['locale' => 'zh-CN'])}}">简体中文</a>
            <a href="{{url('lang', ['locale' => 'zh-tw'])}}">繁體中文</a>
            <a href="{{url('lang', ['locale' => 'en'])}}">English</a>
            <a href="{{url('lang', ['locale' => 'ko'])}}">한국어</a>
        @else
        @endif
    </nav> --}}
    <!-- BEGIN LOGIN FORM -->
    <div id="login-panel" v-show="!isReg">
        <h2 class="my-title">用户登陆</h2>
        <form class="login-form" method="post" @submit.prevent>
            <div class="alert alert-danger" v-show="loginTip!=null" style="display: none">
                <button class="close" @click.prevent="closeTip('loginTip')"></button>
                <span> @{{loginTip}} </span>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">{{trans('login.username')}}</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('login.username')}}" name="username" v-model.trim="loginForm.username" />
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">{{trans('login.password')}}</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('login.password')}}" name="password" v-model="loginForm.password" value="{{Request::old('password')}}" />
                <input type="hidden" name="_token" v-model="loginForm._token" value="{{csrf_token()}}" />
            </div>
            @if($is_captcha)
                <div class="form-group" style="margin-bottom:65px;">
                    <label class="control-label visible-ie8 visible-ie9">{{trans('login.captcha')}}</label>
                    <input class="form-control form-control-solid placeholder-no-fix" style="width:60%;float:left;" type="text" autocomplete="off" placeholder="{{trans('login.captcha')}}" name="captcha" v-model="loginForm.captcha" value="" />
                    <img :src="captcha" onclick="this.src='/captcha/default?'+Math.random()" alt="{{trans('login.captcha')}}" style="float:right;" />
                </div>
            @endif
            <div class="form-actions">
                <div class="pull-left">
                    <label class="rememberme mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="remember" value="1"> {{trans('login.remember')}}
                        <span></span>
                    </label>
                </div>
                <div class="pull-right forget-password-block">
                    <a href="{{url('resetPassword')}}" class="forget-password">{{trans('login.forget_password')}}</a>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn red btn-block uppercase" @click.prevent="submitLogin">{{trans('login.login')}}</button>
            </div>
            <div class="create-account">
                <p>
                    <a href="javascript:void(0)" @click="showReg" class="btn-primary btn">{{trans('login.register')}}</a>
                </p>
            </div>
        </form>
    </div>
    <!-- END LOGIN FORM -->

    <!-- Register Form -->
    <div id="reg-panel" style="display: none" v-show="isReg">
        <h2 class="my-title">注册新用户</h2>
        <form class="register-form" method="post" @submit.prevent style="display: block;">
            @if($is_register)
                <div class="alert alert-danger" v-show="regTip!=null" style="display: none">
                    <button class="close" @click.prevent="regTip=null"></button>
                    <span> @{{regTip}} </span>
                </div>
                <div class="alert alert-success" v-show="regSuccess" style="display: none">
                    <button class="close" @click.prevent="regSuccess=false"></button>
                    <span> @{{regSuccessMsg}} </span>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">{{trans('register.username')}}</label>
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('register.username_placeholder')}}" name="username" v-model="regForm.username" required />
                    {{-- <input type="hidden" name="register_token" value="{{Session::get('register_token')}}" /> --}}
                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                    <input type="hidden" name="aff" value="{{Session::get('register_aff')}}" />
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">{{trans('register.password')}}</label>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('register.password')}}" name="password" v-model="regForm.password" required />
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">{{trans('register.retype_password')}}</label>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('register.retype_password')}}" name="repassword" v-model="regForm.repassword"  required />
                </div>
                @if($is_invite_register)
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">{{trans('register.code')}}</label>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('register.code')}}" name="code" v-model="regForm.code" required />
                    </div>
                    @if($is_free_code)
                        <p class="hint"> <a href="{{url('free')}}" target="_blank">{{trans('register.get_free_code')}}</a> </p>
                    @endif
                @endif
                @if($is_captcha)
                <div class="form-group" style="margin-bottom:75px;">
                    <label class="control-label visible-ie8 visible-ie9">{{trans('register.captcha')}}</label>
                    <input class="form-control placeholder-no-fix" style="width:60%;float:left;" type="text" autocomplete="off" placeholder="{{trans('register.captcha')}}" name="captcha" v-model="regForm.captcha" required />
                    <img v-if="isReg" :src="captcha" onclick="this.src='/captcha/default?'+Math.random()" alt="{{trans('register.captcha')}}" style="float:right;" />
                </div>
                @endif
                <div class="form-group margin-top-20 margin-bottom-20">
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="tnc" checked disabled /> {{trans('register.tnc_button')}}
                        <a href="javascript:showTnc();"> {{trans('register.tnc_link')}} </a>
                        <span></span>
                    </label>
                </div>
            @else
                {{-- 没有开放注册 --}}
                <div class="alert alert-danger">
                    <span> {{trans('register.register_alter')}} </span>
                </div>
            @endif
            <div class="form-actions">
                <button type="button" class="btn btn-default" @click.prevent="showLogin">{{trans('register.back')}}</button>
                @if($is_register)
                    <button v-show="!showRegWating" class="btn red pull-right" @click.prevent="submitReg">{{trans('register.submit')}}</button>
                    <button v-show="showRegWating" class="btn red uppercase pull-right" disabled @click.prevent>处理中...</button>
                @endif
            </div>
        </form>
    </div>
    <!-- END Register Form -->
</div>

<!-- END LOGIN -->
<!--[if lt IE 9]>
<script src="/assets/global/plugins/respond.min.js"></script>
<script src="/assets/global/plugins/excanvas.min.js"></script>
<script src="/assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
{{-- <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/global/plugins/jquery-validation/js/localization/messages_zh.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/pages/scripts/login.js" type="text/javascript"></script> --}}

<!-- Global site tag (gtag.js) - Google Analytics -->
{{-- 
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122312249-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-122312249-1');
</script>
 --}}
<script src="https://cdn.bootcss.com/es6-promise/4.1.1/es6-promise.auto.min.js"></script>
<script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
{{-- <script src="https://unpkg.com/element-ui@2.4.8/lib/index.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/underscore@1.9.1/underscore.min.js"></script>

<script src="/js/common-setting.js?001" type="text/javascript"></script>
<script src="/js/login.js?006" type="text/javascript"></script>

<!-- 统计 -->
{!! $website_analytics !!}
<!-- 客服 -->
{!! $website_customer_service !!}
</body>

</html>

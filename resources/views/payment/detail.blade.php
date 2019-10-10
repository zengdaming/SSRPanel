@extends('user.layouts')

@section('css')

@endsection
@section('title', trans('home.panel'))
@section('content')
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="padding-top:0;">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="alert alert-info" style="text-align: center;">
                    请使用<strong style="color:red;">微信</strong>扫描如下二维码
                </div>
                <div class="row" style="text-align: center; font-size: 1.05em;">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-hover table-light">
                                <tr>
                                    <td align="right" width="50%">服务名称：</td>
                                    <td align="left" width="50%">{{$payment->order->goods->name}}</td>
                                </tr>
                                <tr>
                                    <td align="right">应付金额：</td>
                                    <td align="left">{{$payment->amount}} 元</td>
                                </tr>
                                <tr>
                                    <td align="right">有效期：</td>
                                    <td align="left">{{$payment->order->goods->days}} 天</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        扫描下方二维码进行付款（可截图再扫描）
                                        <br>
                                        请于15分钟内支付，到期未支付订单将自动关闭
                                        <br>
                                        支付后，请稍作等待，账号状态会自动更新
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center">
                                        {{-- <img src="{{$payment->qr_local_url}}"/> --}}
                                        <div align="center" id="qr" style="text-align: center;width: 303px"></div>
                                        <div id="success-tip" style="display: none">
                                            <i class="fa fa-check-circle" aria-hidden="true" style="color:green;font-size: 100px;line-height: 100px"></i>
                                            <h3>支付成功</h3>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- END CONTENT BODY -->
@endsection
@section('script')
    <script src="/js/layer/layer.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://static.runoob.com/assets/qrcode/qrcode.min.js"></script>
    <script>
    if (typeof QRCode == 'undefined') {
    document.write(unescape("%3Cscript src='/assets/global/plugins/qrcode.min.js' type='text/javascript'%3E%3C/script%3E")); 
    }
    </script>
    <script type="text/javascript">

        // 每2秒查询一次订单状态
        var task = null;
        var $successTip = undefined;
        var $qr = undefined;
        $(document).ready(function(){
            task = setInterval("getStatus()", 1000);

            var qrcode = new QRCode(document.getElementById("qr"), {
                width : 300,
                height : 300
            });

            $successTip = $("#success-tip");
            $qr         = $("#qr");
            qrcode.makeCode('{{$payment->qr_url}}');
        });

        // 检查支付单状态
        function getStatus () {
            var sn = '{{$payment->sn}}';

            $.get("{{url('payment/getStatus')}}", {sn:sn}, function (ret) {
                console.log(ret);
                if (ret.status == 'success') {
                    window.clearInterval(task);
                    $successTip.show();
                    $qr.hide();
                    //layer.msg(ret.message, {time:5000});
                }
            });
        }
    </script>
@endsection
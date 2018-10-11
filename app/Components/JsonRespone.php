<?php
/**
* 通用的json回复
* code：错误码：参考http的返回码：0或者200是正常，400用户请求错误，403是权限不足，404资源不存在，500是系统错误或未知错误
* data：数据
* message: 错误信息
*/
namespace App\Components;
class JsonRespone{

    protected $code;
    protected $data;
    protected $message;

    private function init($code,$data,$msg){
        $this->code    = $code;
        $this->data    = $data;
        $this->message = $msg;
    }

    public function success($data,$msg="成功"){
        $this->init(200,$data,$msg);
        return $this->toJson();
    }

    //400 用户请求的数据有问题
    public function wrong($msg){
        $this->init(400,null,$msg);
        return $this->toJson();
    }

    // 403错误，没有执行权限
    public function forbid($msg="没有执行权限"){
        $this->init(403,null,$msg);
        return $this->toJson();
    }

    // 500系统错误
    public function error($msg="系统错误"){
        $this->init(500,null,$msg);
        return $this->toJson();
    }

    public function toJson(){
        //根据错误码设置状态字段,详情请参阅http返回码（这是为了兼容才保留）
        $status = 'success';
        if( $this->code >= 0 && $this->code < 300 ){
            $status = 'success';
        }else if( $this->code >= 400 && $this->code < 500 ){
            $status = 'fail';
        }else if( $this->code >= 500 ){
            $status = 'error';
        }else{
            $status = 'error';//其他情况都属于出错
        }

        return [
            'status'  => $status,
            'code'    => $this->code,
            'data'    => $this->data,
            'message' => $this->message
        ];
    }
}
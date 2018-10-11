new Vue({
    el:'#app',
    data:{
        isReg     : false,  //是否显示注册面板
        loginTip  : null,   //登陆错误提示
        regTip    : null,   //注册错误提示
        captcha   : null,   //验证码url
        showRegWating:false,//是否显示注册等待提示
        regSuccess    :false,
        regSuccessMsg :null,
        loginForm : {       //登陆的表单数据
            username:null,
            password:null,
            captcha:null,
            _token:null,
        },

        regForm : {         //注册的表单数据
            username:null,
            password:null,
            repassword:null,
            code:null,
            captcha:null,
            _token:null,
        } 
    },

    mounted:function(){
        this.refreshCaptcha();//初始化验证码
    },

    methods:{
        // 提交登陆信息
        submitLogin: _.debounce(function(){
            var _this = this;
            this.$http.post('login',this.loginForm)
            .then( function(r){
                if(r.status!='success'){throw new Error(r.message)};
                window.location.href=r.data.jump;
            })

            .catch(function(e){
                _this.loginTip = e.message;
                console.error('登陆失败:'+e.message);
            });
        },1000,true),

        // 提交注册请求，已经做了防抖动处理
        submitReg: _.debounce(function(){
            var _this = this;
            _this.showRegWating = true;
            this.$http.post('/register',this.regForm)
            .then(function(r){
                if(r.status!='success'){throw new Error(r.message)};
                _this.regSuccess=true;
                _this.regSuccessMsg = r.message;

            })

            .catch(function(e){
                _this.regTip = e.message;
                console.error('注册失败:'+e.message);
            })

            .then(function(r){
                _this.showRegWating = false;
            });
        },1500,true),

        showReg:function(){
            this.isReg = true;
            this.closeTip('all');
        },

        showLogin:function(){
            this.isReg = false;
            this.closeTip('all');
        },

        closeTip:function(type){
            var _this = this;
            if (type==='loginTip') {
                _this.loginTip = null;
            }
            else if ( type==='regTip') {
                _this.regTip = null;
            }
            else{
                _this.regTip = _this.loginTip = null;
            }
        },
        //刷新验证码
        refreshCaptcha:function(){
            this.captcha = '/captcha/default?'+Math.random();
        }
    }
});
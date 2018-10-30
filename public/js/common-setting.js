axios.interceptors.response.use(
    function(response){
        
        if( response.status===404 ){
            throw new Error('后台没有该资源');
        }
        var ct = response.headers['content-type'].toLowerCase().trim();
        if ( ct !='application/json;charset=utf-8' && ct !='application/json' )
        {
            throw new Error('无权操作，你需要重新登陆');
        }
        //收到信息后的拦截器：直接返回data区的数据,data区的数据会自动json化
        return response.data;
    },
    function( error ){//发生错误或抛出异常的时候执行的方法
        console.error(error);
        return Promise.reject(error);
    }
);

//将axios封装到原型链中，这样就可以在vue中直接使用this.$http来调用，这样方法内的this就是vue实例
//同时这样可以实现进一步封装ajax方法的效果，如果以后需要更换不同的ajax的库，也可以轻松迁移
if("undefined"!=typeof Vue){
    Vue.prototype.$http = axios;
}
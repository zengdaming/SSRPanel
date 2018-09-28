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
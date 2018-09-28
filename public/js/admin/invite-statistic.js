var vue = new Vue({
    el: '#app',
    data: {
        refList  : [],
        tradeList: [], //交易明细记录
        showLoading   : false, //是否显示加载提示
        dialogVisible : false,//是否显示交易明细弹窗
    },
    mounted: function() {
        this.reload();
    },
    methods: {
        reload: function() {
            var url   = 'inviteStatistic/list';
            var _this = this;
            _this.showLoading = true;

            axios.post(url)
                .then(function(rsp) {
                    if (rsp.status != 'success') {
                        throw new Error(rsp.msg);
                    }
                    _this.refList = rsp.data.refList;
                })
                .catch(function(err) {
                    _this.$alert(err.message,'错误信息');
                })
                .then(function(){
                    _this.showLoading = false;
                });
        },

        // 显示交易明细记录
        showTradeLog: function(uid) {
            var url = 'inviteStatistic/tradeLog?uid=' + uid;
            var _this = this;
            _this.dialogVisible=true;
            axios.get(url)
                .then(function(rsp) {
                    console.log(rsp);
                    if (rsp.status != 'success') {
                        throw new Error(rsp.msg);
                    }
                    _this.tradeList = rsp.data.tradeLogList;

                })
                .catch(function(err) {
                    console.log(err);
                    _this.$alert(err.message,'错误信息');
                });
        }
    }
});
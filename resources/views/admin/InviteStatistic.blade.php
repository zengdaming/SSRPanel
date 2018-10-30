@extends('admin.layouts')

@section('css')
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.4.8/lib/theme-chalk/index.css">
@endsection
@section('title', '推广统计')
@section('content')
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="padding-top:0;">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <span class="caption-subject bold uppercase"> 推广统计 </span>
                        </div>
                    </div>
                    <div id="app">
                    <el-table :data="refList" style="width: 100%" v-loading="showLoading">
                        <el-table-column prop="username" label="推广人" min-width="120"></el-table-column>
                        <el-table-column prop="count" label="推广数量" min-width="70">
                            <template slot-scope="scope">
                                <span>@{{scope.row.count}} 人</span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="totalAmount" label="总金额" min-width="100">
                            <template slot-scope="scope">
                                <span>@{{scope.row.totalAmount / 100}} 元</span>
                            </template>
                        </el-table-column>
                        <el-table-column label="操作">
                            <template slot-scope="scope">
                                <el-button size="small" @click="showTradeLog(scope.row.uid)">查看交易记录</el-button>
                            </template>
                        </el-table-column>
                    </el-table>

                    {{-- 交易记录明细 --}}
                    <el-dialog title="交易明细" :visible.sync="dialogVisible">
                        <el-table :data="tradeList" style="width: 100%" v-loading="tradeListLoading">
                        <el-table-column prop="user.username" label="用户"></el-table-column>
                        <el-table-column prop="amount" label="交易金额">
                            <template slot-scope="scope">
                                <span>@{{scope.row.amount}} 元</span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="created_at" label="交易时间"></el-table-column>
                    </el-dialog>
                    </div> <!--end of #app-->
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- END CONTENT BODY -->
@endsection
@section('script')
    {{-- <script src="/js/layer/layer.js" type="text/javascript"></script> --}}
    <script src="https://cdn.bootcss.com/es6-promise/4.1.1/es6-promise.auto.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
    <!-- import Vue before Element -->
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <!-- import JavaScript -->
    <script src="https://unpkg.com/element-ui@2.4.8/lib/index.js"></script>
    <script src="/js/common-setting.js?004" type="text/javascript"></script> 
    <script src="/js/admin/invite-statistic.js?002" type="text/javascript"></script> 
@endsection
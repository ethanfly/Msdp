<template>
    <el-row>
        <el-col :span="8">
            <el-form ref="form" :model="form" label-width="100px" :rules="rules">
                <el-form-item label="市场选择" prop="selectMarket">
                    <el-cascader
                            expand-trigger="click"
                            :options="market"
                            v-model="form.selectMarket"
                            @change="changes"
                            style="width: 100%">
                    </el-cascader>
                </el-form-item>
                <el-form-item label="店铺名称" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="经营者" prop="manager">
                    <el-input v-model="form.manager"></el-input>
                </el-form-item>
                <el-form-item label="执照号码" prop="number">
                    <el-input v-model="form.number"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit('form')">提交</el-button>
                </el-form-item>
            </el-form>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        data() {
            return {
                id: this.$route.params.id,
                market: [],
                form: {
                    name: '',
                    manager: '',
                    number: '',
                    selectMarket: []
                },
                rules: {
                    name: [
                        {required: true, message: '请输入名称', trigger: 'blur'},
                    ],
                    manager: [
                        {required: true, message: '请输入经营者', trigger: 'blur'},
                    ],
                    number: [
                        {required: true, message: '请输入执照号', trigger: 'blur'},
                    ],
                    selectMarket: [
                        {required: true, type: 'array',message: '请选择市场', trigger: 'change'}
                    ],
                }
            }
        },
        mounted(){
            this.axios.get(api.market, {
                params: {
                    all: true
                }
            }).then(r => {
                let markets = r.data.data;
                this.market = _.flatMap(markets, item => {
                    let types = _.flatMap(item.types, r => {
                        return [{
                            value: r.id,
                            label: r.name
                        }];
                    });
                    return [
                        {
                            value: item.id,
                            label: item.name,
                            children: types
                        }
                    ];
                });
            });
            if (this.$route.params.id) {
                this.axios.get(api.shop + this.id).then(r => {
                    this.form.name = r.data.data.name;
                    this.form.manager = r.data.data.manager;
                    this.form.number = r.data.data.number;
                    this.form.selectMarket = r.data.data.selectMarket;
                })
            }
        },
        watch: {
            '$route' (to, from) {
                // 对路由变化作出响应...
                if (to.path === '/shop/add') {
                    this.form.name = '';
                    this.form.manager = '';
                    this.form.number = '';
                    this.form.selectMarket = [];
                }
            }
        },
        methods: {
            onSubmit(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if (this.$route.name === 'shopEdit') {
                            this.axios.post(api.shop + this.id, {
                                name: this.form.name,
                                manager: this.form.manager,
                                number: this.form.number,
                                market_id: this.form.selectMarket[0],
                                type_id: this.form.selectMarket[1],
                                _method: 'PUT'
                            }).then(r => {
                                if (r.data.code === 1) {
                                    this.$message({
                                        type: 'success',
                                        message: '成功修改!'
                                    });
                                } else {
                                    this.$message({
                                        type: 'error',
                                        message: '修改失败！'
                                    });
                                }
                            })
                        } else {
                            this.axios.post(api.shop, {
                                name: this.form.name,
                                manager: this.form.manager,
                                number: this.form.number,
                                market_id: this.form.selectMarket[0],
                                type_id: this.form.selectMarket[1]
                            }).then(r => {
                                if (r.data.code === 1) {
                                    this.$message({
                                        type: 'success',
                                        message: '成功添加!'
                                    });
                                } else {
                                    this.$message({
                                        type: 'error',
                                        message: '添加失败！'
                                    });
                                }
                            })
                        }
                    }
                    else {
                        return false;
                    }
                });
            }
        }
    };
</script>

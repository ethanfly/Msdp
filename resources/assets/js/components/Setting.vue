<template>
    <el-row>
        <el-col :xs="24" :sm="18" :md="12">
            <el-form ref="form" :model="setting" label-width="150px">
                <el-form-item label="微信APP_ID">
                    <el-input v-model="setting.WX_APP_ID" placeholder="请输入WX_APP_ID"></el-input>
                </el-form-item>
                <el-form-item label="微信APP_SECRET">
                    <el-input v-model="setting.WX_APP_SECRET" placeholder="请输入WX_APP_SECRET"></el-input>
                </el-form-item>
                <el-form-item label="百度地图AK">
                    <el-input v-model="setting.BD_AK" placeholder="请输入BD_AK" class="mar_b15"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="success" @click="onsubmit()">确定提交</el-button>
                </el-form-item>
            </el-form>
        </el-col>
    </el-row>

</template>

<script>
    export default {
        data () {
            return {
                setting: {
                    WX_APP_ID: '',
                    WX_APP_SECRET: '',
                    BD_AK: ''
                }
            };
        },
        methods: {
            onsubmit(){
                this.axios.post(api.setting, {
                    WX_APP_ID: this.setting.WX_APP_ID,
                    WX_APP_SECRET: this.setting.WX_APP_SECRET,
                    BD_AK: this.setting.BD_AK,
                }).then(r => {
                    if (r.data.code == 1) {
                        this.$notify({
                            title: '成功',
                            message: '修改成功',
                            type: 'success'
                        });
                    }
                });
            }
        },
        mounted(){
            this.axios.get(api.setting).then(r => {
                this.setting.WX_APP_ID = r.data.WX_APP_ID;
                this.setting.WX_APP_SECRET = r.data.WX_APP_SECRET;
                this.setting.BD_AK = r.data.BD_AK;
            });
        }
    };
</script>

<template>
    <el-row>
        <el-col :span="24" class="mar_b15">
            <el-form ref="form" :model="setting" label-width="150px">
                <el-form-item label="首页轮播图比例9:5">
                    <el-upload
                            :action="upload"
                            list-type="picture-card"
                            name="file"
                            :on-preview="handlePictureCardPreview"
                            show-file-list
                            :file-list="files"
                            :on-success="handleSuccess"
                            :on-remove="handleRemove">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                    <el-dialog v-model="dialogVisible" size="tiny">
                        <img width="100%" :src="dialogImageUrl" alt="">
                    </el-dialog>
                </el-form-item>
            </el-form>
        </el-col>
        <el-col :xs="24" :sm="18" :md="12">
            <el-form ref="form" :model="setting" label-width="150px">
                <el-form-item label="微信APP_ID">
                    <el-input v-model="setting.WX_APP_ID" readonly placeholder="请输入WX_APP_ID"></el-input>
                </el-form-item>
                <el-form-item label="微信APP_SECRET">
                    <el-input v-model="setting.WX_APP_SECRET" readonly placeholder="请输入WX_APP_SECRET"></el-input>
                </el-form-item>
                <el-form-item label="百度地图AK">
                    <el-input v-model="setting.BD_AK" readonly placeholder="请输入BD_AK"></el-input>
                </el-form-item>
                <el-form-item label="修改管理员密码">
                    <el-input v-model="setting.PAS" placeholder="请输入要修改的密码" class="mar_b15"></el-input>
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
                upload: api.upload,
                setting: {
                    WX_APP_ID: '',
                    WX_APP_SECRET: '',
                    BD_AK: '',
                    PAS:''
                },
                files: [],
                dialogImageUrl: '',
                dialogVisible: false
            };
        },
        methods: {
            onsubmit(){
                this.axios.post(api.setting, {
                    WX_APP_ID: this.setting.WX_APP_ID,
                    WX_APP_SECRET: this.setting.WX_APP_SECRET,
                    BD_AK: this.setting.BD_AK,
                    PAS:this.setting.PAS
                }).then(r => {
                    if (r.data.code == 1) {
                        this.$notify({
                            title: '成功',
                            message: '修改成功',
                            type: 'success'
                        });
                    }
                });
            },
            handleRemove(file) {
                let url = file.response ? file.response.url : file.url;
                this.axios.delete(api.banner, {
                    data: {
                        banner: url
                    }
                }).then(r => {
                    if (r.data.code == 1) {
                        this.$notify({
                            title: '删除',
                            message: '删除成功',
                            type: 'error'
                        });
                    }
                });
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
                console.log(this.dialogImageUrl);
            },
            handleSuccess(response, file, fileList){
                this.axios.post(api.banner, {
                    banner: response.url
                }).then(r => {
                    if (r.data.code == 1) {
                        this.$notify({
                            title: '添加',
                            message: '添加成功',
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
                this.files = r.data.banner;
            });
        }
    };
</script>
<style>
    .el-upload-list--picture-card .el-upload-list__item {
        width: 320px;
        height: 200px;
    }
</style>
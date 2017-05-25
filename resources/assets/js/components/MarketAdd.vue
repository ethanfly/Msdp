<template>
    <el-row>
        <el-col :span="8">
            <el-form ref="form" :model="form" label-width="100px" :rules="rules">
                <el-form-item label="名称" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="图片" prop="img">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="form.img" :src="form.img" class="avatar">
                        <span class="el-upload">比例8:5不超过2M</span>
                    </el-upload>
                </el-form-item>
                <el-form-item label="百度纬度" prop="latitude">
                    <el-input v-model.number="form.latitude"></el-input>
                </el-form-item>
                <el-form-item label="百度经度" prop="longitude">
                    <el-input v-model.number="form.longitude"></el-input>
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
            let reg = /^[1-9]\d*\.\d*|0\.\d*[1-9]\d*$/;
            var latitudeCheck = (rule, value, callback) => {
                console.log(reg.test(value));
                if (!value) {
                    return callback(new Error('纬度不能为空'));
                }
                if (!reg.test(value)) {
                    return callback(new Error('请输入小数值'));
                } else {
                    if (value > 90 || value < -90) {
                        return callback(new Error('纬度范围在-90~+90之间'));
                    }
                }
                callback();
            };
            var longitudeCheck = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('经度不能为空'));
                }
                if (!reg.test(value)) {
                    return callback(new Error('请输入小数值'));
                } else {
                    if (value > 180 || value < -180) {
                        return callback(new Error('经度范围在-180~+180之间'));
                    }
                }
                callback();
            };
            return {
                upload: api.upload,
                id: this.$route.params.id,
                form: {
                    img: '',
                    name: '',
                    latitude: '',
                    longitude: '',
                },
                rules: {
                    name: [
                        {required: true, message: '请输入名称', trigger: 'blur'},
                    ],
                    img: [
                        {required: true, message: '请上传图片', trigger: 'blur'},
                    ],
                    latitude: [
                        {validator: latitudeCheck, trigger: 'blur'}
                    ],
                    longitude: [

                        {validator: longitudeCheck, trigger: 'blur'}
                    ],
                }
            }
        },
        mounted(){
            if (this.$route.params.id) {
                this.axios.get(api.market + this.id).then(r => {
                    this.form.name = r.data.data.name;
                    this.form.img = r.data.data.img;
                    this.form.latitude = Number(r.data.data.latitude);
                    this.form.longitude = Number(r.data.data.longitude);
                })
            }
        },
        watch: {
            '$route' (to, from) {
                // 对路由变化作出响应...
                if (to.path === '/market/add') {
                    this.form.name = '';
                    this.form.img = '';
                    this.form.latitude = '';
                    this.form.longitude = '';
                }
            }
        },
        methods: {
            onSubmit(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if (this.$route.name === 'marketEdit') {
                            this.axios.post(api.market + this.id, {
                                form: this.form,
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
                            this.axios.post(api.market, {
                                form: this.form,
                            }).then(r => {
                                console.log(r.data);
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
            },
            handleAvatarSuccess(res, file) {
                this.form.img = res.url;
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/gif';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            }
        }
    };
</script>

<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #20a0ff;
    }

    .el-upload {
        font-size: 28px;
        color: #8c939d;
        width: 320px;
        height: 200px;
        line-height: 200px;
        text-align: center;
    }

    .avatar {
        width: 320px;
        height: 200px;
        display: block;
    }
</style>
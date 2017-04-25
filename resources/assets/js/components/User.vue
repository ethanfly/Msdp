<template>
    <el-row>
        <el-col>
            <el-table
                    :data="users"
                    border
                    class="mar_b15"
                    style="width: 100%;vertical-align: middle"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        prop="id"
                        label="序号"
                        sortable
                        width="100">
                </el-table-column>
                <el-table-column
                        prop="created_at"
                        sortable
                        label="创建日期">
                </el-table-column>
                <el-table-column
                        prop="nickname"
                        label="姓名">
                </el-table-column>
                <el-table-column
                        prop="avatarUrl"
                        width="101"
                        label="头像">
                    <template scope="scope">
                        <img :src="scope.row.avatarUrl" width="65" style="margin: 18px 0 10px 0"/>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="gender"
                        label="性别">
                </el-table-column>
                <el-table-column
                        prop="city"
                        label="城市">
                </el-table-column>
                <el-table-column
                        prop="province"
                        label="省份">
                </el-table-column>
                <el-table-column
                        label="身份">
                    <template scope="scope">
                        <span v-if="scope.row.type===1">普通用户</span>
                        <span v-else-if="scope.row.type===2">市场管理者</span>
                        <span v-else-if="scope.row.type===3">政府部门</span>
                    </template>
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template scope="scope">
                        <el-dropdown trigger="click" menu-align="start"
                                     @command="handleSet(scope.row,$event)">
                            <el-button type="primary" size="mini">
                                设置为<i class="el-icon-caret-bottom el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item command="1">普通用户</el-dropdown-item>
                                <el-dropdown-item command="2">市场经营者</el-dropdown-item>
                                <el-dropdown-item command="3">政府部门</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
        <el-col>
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="pagination.current"
                    :page-sizes="[10, 20, 30, 40]"
                    :page-size="pagination.size"
                    :total="pagination.total"
                    layout="total, sizes, prev, pager, next, jumper"
                    style="margin: 0 auto;padding: 0;text-align: center"
            >
            </el-pagination>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                multipleSelection: [],
                pagination: {
                    current: 1,
                    size: 10,
                    total: 0
                }
            }
        },
        mounted(){
            this.axios.get(api.user, {
                params: {
                    size: this.pagination.size
                }
            }).then(r => {
                this.users = r.data.data;
                this.pagination.current = r.data.current_page;
                this.pagination.total = r.data.total;
            });
        },
        methods: {
            handleSet(row, command) {
                let id = row.id;
                row.type = Number(command);
                console.log(api.user + id);
                this.axios.post(api.user + id, {
                    type: row.type,
                    _method: 'PUT'
                }).then(r => {
                    if (r.data.code === 1) {
                        this.$notify({
                            title: '成功',
                            message: '修改成功',
                            type: 'success'
                        });
                    }else{
                        this.$notify({
                            title: '错误',
                            message: '修改失败',
                            type: 'error'
                        });
                    }
                });
            },
            handleSizeChange(val) {
                this.pagination.size = val;
                this.axios.get(api.user, {
                    params: {
                        size: this.pagination.size,
                        page: this.pagination.current
                    }
                }).then(r => {
                    this.users = r.data.data;
                    this.pagination.current = r.data.current_page;
                    this.pagination.total = r.data.total;
                });
            },
            handleCurrentChange(val) {
                this.pagination.current = val;
                this.axios.get(api.user, {
                    params: {
                        size: this.pagination.size,
                        page: this.pagination.current
                    }
                }).then(r => {
                    this.users = r.data.data;
                    this.pagination.current = r.data.current_page;
                    this.pagination.total = r.data.total;
                });
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
                let id = [];
                for (let i = 0; i < val.length; i++) {
                    id.push(val[i].id);
                }
            }
        },

    }
</script>
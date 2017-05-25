<template>
    <el-row>
        <el-col>
            <el-table
                    :data="comments"
                    border
                    class="mar_b15"
                    style="width: 100%;vertical-align: middle"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        label="序号"
                        prop="index"
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
                        prop="field1"
                        label="评分一">
                </el-table-column>
                <el-table-column
                        prop="field2"
                        label="评分二">
                </el-table-column>
                <el-table-column
                        prop="field3"
                        label="评分三">
                </el-table-column>
                <el-table-column
                        prop="field4"
                        label="评分四">
                </el-table-column>
                <el-table-column
                        prop="feel"
                        label="印象">
                </el-table-column>
                <el-table-column
                        prop="others"
                        label="其他">
                </el-table-column>
                <el-table-column
                        prop="market"
                        label="市场">
                </el-table-column>
                <el-table-column
                        prop="shop"
                        label="店铺">
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template scope="scope">
                        <el-button type="danger" size="mini" @click="deleteComment(scope.row)">删除</el-button>
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
                comments: [],
                multipleSelection: [],
                pagination: {
                    current: 1,
                    size: 10,
                    total: 0
                }
            }
        },
        mounted(){
            this.axios.get(api.comment, {
                params: {
                    size: this.pagination.size
                }
            }).then(r => {
                let pagination = this.pagination;
                this.comments = _.map(r.data.data, function ($item, $index) {
                    $index = (pagination.current - 1) * pagination.size + $index + 1;
                    $item.index = $index;
                    return $item;
                });

                this.pagination.current = r.data.current_page;
                this.pagination.total = r.data.total;
            });
        },
        methods: {
            deleteComment(row){
                this.axios.delete(api.comment + row.id,).then(r => {
                    if (r.data.code === 1) {
                        this.comments.splice(this.comments.indexOf(row), 1);
                        this.$notify({
                            title: '成功',
                            message: '删除成功',
                            type: 'success'
                        });
                    } else {
                        this.$notify({
                            title: '错误',
                            message: '删除失败',
                            type: 'error'
                        });
                    }
                });
            },
            handleSizeChange(val) {
                this.pagination.size = val;
                this.axios.get(api.comment, {
                    params: {
                        size: this.pagination.size,
                        page: this.pagination.current
                    }
                }).then(r => {
                    let pagination = this.pagination;
                    this.comments = _.map(r.data.data, function ($item, $index) {
                        $index = (pagination.current - 1) * pagination.size + $index + 1;
                        $item.index = $index;
                        return $item;
                    });
                    this.pagination.current = r.data.current_page;
                    this.pagination.total = r.data.total;
                });
            },
            handleCurrentChange(val) {
                this.pagination.current = val;
                this.axios.get(api.comment, {
                    params: {
                        size: this.pagination.size,
                        page: this.pagination.current
                    }
                }).then(r => {
                    let pagination = this.pagination;
                    this.comments = _.map(r.data.data, function ($item, $index) {
                        $index = (pagination.current - 1) * pagination.size + $index + 1;
                        $item.index = $index;
                        return $item;
                    });
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
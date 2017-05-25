<template>
    <el-row>
        <el-col :span="6">
            <el-input
                    placeholder="请输入关键字"
                    icon="search"
                    v-model="search"
                    :on-icon-click="searchClick"
                    class="mar_b15">
            </el-input>
        </el-col>
        <el-col>
            <el-table
                    :data="markets"
                    fit
                    border
                    class="mar_b15"
                    style="width: 100%;vertical-align: middle"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        prop="index"
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
                        prop="name"
                        label="名称">
                </el-table-column>
                <el-table-column
                        prop="img"
                        width="136"
                        label="图片">
                    <template scope="scope">
                        <img :src="scope.row.img" width="100" style="margin: 18px 0 10px 0"/>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="latitude"
                        label="纬度">
                </el-table-column>
                <el-table-column
                        prop="longitude"
                        label="经度">
                </el-table-column>
                <el-table-column
                        label="分类">
                    <template scope="scope">
                        <el-tag
                                :key="tag"
                                v-for="tag in scope.row.types"
                                :closable="true"
                                :close-transition="true"
                                @close="handleClose(tag,scope.row.types)"
                        >
                            {{tag.name}}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template scope="scope">
                        <el-button type="success" size="mini" @click="openBox(scope.row)">添加分类</el-button>
                        <el-button type="danger" size="mini" @click="deleteMarket(scope.row)">删除</el-button>
                        <el-button type="info" size="mini" @click="editMarket(scope.row)">编辑</el-button>
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
                markets: [],
                multipleSelection: [],
                pagination: {
                    current: 1,
                    size: 10,
                    total: 0
                },
                search: ''
            }
        },
        mounted(){
            this.listGet();
        },
        methods: {
            listGet(){
                this.axios.get(api.market, {
                    params: {
                        search: this.search,
                        size: this.pagination.size,
                        page: this.pagination.current
                    }
                }).then(r => {
                    let pagination = this.pagination;
                    this.markets = _.map(r.data.data, function ($item, $index) {
                        $index = (pagination.current - 1) * pagination.size + $index + 1;
                        $item.index = $index;
                        return $item;
                    });
                    this.pagination.current = r.data.meta.pagination.current_page;
                    this.pagination.total = r.data.meta.pagination.total;
                });
            },
            handleSizeChange(val) {
                this.pagination.size = val;
                this.listGet();
            },
            handleCurrentChange(val) {
                this.pagination.current = val;
                this.listGet();
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
                let id = [];
                for (let i = 0; i < val.length; i++) {
                    id.push(val[i].id);
                }
            },
            handleClose(tag, rows) {
                rows.splice(rows.indexOf(tag), 1);
                this.axios.delete(api.type + tag.id,).then(r => {
                    if (r.data.code === 1) {
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
            openBox(row){
                this.$prompt('请填写名称', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(({value}) => {
                    this.axios.post(api.type, {
                        name: value,
                        market_id: row.id
                    }).then(r => {
                        if (r.data.code === 1) {
                            row.types.push(r.data.data);
                            this.$message({
                                type: 'success',
                                message: '成功添加: ' + value
                            });
                        } else {
                            this.$message({
                                type: 'error',
                                message: '添加失败！'
                            });
                        }
                    });
                })
            },
            deleteMarket(row){
                this.axios.delete(api.market + row.id,).then(r => {
                    if (r.data.code === 1) {
                        this.markets.splice(this.markets.indexOf(row), 1);
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
            editMarket(row){
                this.$router.push({name: 'marketEdit', params: {id: row.id}})
            },
            searchClick(){
                this.listGet();
            }
        },
    }


</script>
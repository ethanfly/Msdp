<template>
    <el-row>
        <el-col :span="6">
            <el-input
                    placeholder="请输入名称，经营者，执照号搜索"
                    icon="search"
                    v-model="search"
                    :on-icon-click="searchClick"
                    class="mar_b15">
            </el-input>
        </el-col>
        <el-col :span="4" :offset="1">
            <el-cascader
                    expand-trigger="click"
                    :options="market"
                    v-model="selectMarket"
                    @change="changes"
                    placeholder="根据市场筛选"
                    style="width: 100%">
            </el-cascader>
        </el-col>
        <el-col>
            <el-table
                    :data="shops"
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
                        prop="manager"
                        label="经营者">
                </el-table-column>
                <el-table-column
                        prop="number"
                        label="执照号">
                </el-table-column>
                <el-table-column
                        prop="market_type"
                        label="所属市场及分类">
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template scope="scope">
                        <el-button type="danger" size="mini" @click="deleteShop(scope.row)">删除</el-button>
                        <el-button type="info" size="mini" @click="editShop(scope.row)">编辑</el-button>
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
                market: [],
                selectMarket: [],
                shops: [],
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
            this.listGet();
        },
        methods: {
            changes(value){
                this.listGet(value);
                this.search = '';
            },
            listGet(market_type){
                this.axios.get(api.shop, {
                    params: {
                        market_type: market_type,
                        search: this.search,
                        size: this.pagination.size,
                        page: this.pagination.current
                    }
                }).then(r => {
                    let pagination = this.pagination;
                    this.shops = _.map(r.data.data, function ($item, $index) {
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
            deleteShop(row){
                this.axios.delete(api.shop + row.id,).then(r => {
                    if (r.data.code === 1) {
                        this.shops.splice(this.shops.indexOf(row), 1);
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
            editShop(row){
                this.$router.push({name: 'shopEdit', params: {id: row.id}})
            },
            searchClick(){
                this.selectMarket = [];
                this.listGet();
            }
        },
    }


</script>
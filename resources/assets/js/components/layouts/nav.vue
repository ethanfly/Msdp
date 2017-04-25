<template>
    <el-menu :default-active="this.$route.path" router unique-opened class="el-menu-vertical-demo nav-menu"
             @open="handleOpen"
             @close="handleClose"
             theme="dark">
        <el-menu-item :index="route.setting.path" :route="route.setting"><i class="fa fa-cog"></i>设置参数</el-menu-item>
        <el-menu-item :index="route.user.path" :route="route.user"><i class="fa fa-user"></i>用户管理</el-menu-item>
        <el-submenu index="market">
            <template slot="title"><i class="fa fa-shopping-basket"></i>市场管理</template>
            <el-menu-item :index="route.market.add.path" :route="route.market.add"><i class="fa fa-plus"></i>增加
            </el-menu-item>
            <el-menu-item :index="route.market.list.path" :route="route.market.list"><i class="fa fa-list"></i>查看
            </el-menu-item>
        </el-submenu>
        <el-submenu index="shop">
            <template slot="title"><i class="fa fa-th-large"></i>商铺管理</template>
            <el-menu-item :index="route.shop.add.path" :route="route.shop.add"><i class="fa fa-plus"></i>增加
            </el-menu-item>
            <el-menu-item :index="route.shop.list.path" :route="route.shop.list"><i class="fa fa-list"></i>查看
            </el-menu-item>
        </el-submenu>
    </el-menu>
</template>

<script>
    import Setting from '../Setting.vue'
    export default {
        data(){
            return {
                route: {
                    setting: {path: '/'},
                    user: {path: '/user'},
                    market: {
                        add: {path: '/market/add'},
                        list: {path: '/market'}
                    },
                    shop: {
                        add: {path: '/shop/add'},
                        list: {path: '/shop'}
                    },
                }
            }
        },
        mounted(){
        },
        methods: {
            handleOpen(key, keyPath) {
//                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
//                console.log(key, keyPath);
            }
        }
    }
</script>

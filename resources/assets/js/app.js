/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "element-ui/lib/theme-default/index.css";
import VueRouter from "vue-router";
import axios from "axios";
import VueAxios from "vue-axios";

import User from "./components/User.vue";
import Setting from "./components/Setting.vue";
import MarketList from "./components/MarketList.vue";
import MarketAdd from "./components/MarketAdd.vue";
import ShopList from "./components/ShopList.vue";
import ShopAdd from "./components/ShopAdd.vue";
import CommentList from "./components/CommentList.vue";

require('./bootstrap');
require('font-awesome/css/font-awesome.min.css');

window.Vue = require('vue');
window.ElementUI = require('element-ui');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueAxios, axios);
Vue.use(VueRouter);
Vue.use(ElementUI);

Vue.component('nav-menu', require('./components/layouts/nav.vue'));

window.api = {
    user: 'api/admin/user/',
    market: 'api/admin/market/',
    type: 'api/admin/type/',
    shop: 'api/admin/shop/',
    setting: 'api/admin/setting/',
    upload: 'api/admin/upload/',
    banner: 'api/admin/banner/',
    comment: 'api/admin/comment/'
};

const routes = [
    {path: '/', component: Setting},
    {path: '/user', component: User},
    // {path: '/setting', component: Setting},
    {path: '/market', component: MarketList},
    {path: '/market/:id(\\d+)', component: MarketAdd, name: 'marketEdit'},
    {path: '/market/add', component: MarketAdd, name: 'marketAdd'},
    {path: '/shop', component: ShopList},
    {path: '/shop/:id(\\d+)', component: ShopAdd, name: 'shopEdit'},
    {path: '/shop/add', component: ShopAdd, name: 'shopAdd'},
    {path: '/comment', component: CommentList}
];

const router = new VueRouter({
    routes // （缩写）相当于 routes: routes
});

const app = new Vue({
    el: '#app',
    data(){
        return {
            routes
        };
    },
    router
});

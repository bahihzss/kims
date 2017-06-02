import Vue from 'vue'
import VueRouter from 'vue-router'

import Index from './components/index/Index.vue'
import Editor from './components/index/Editor.vue'

Vue.use(VueRouter);

const routes = [
    {path: '/', component: Index, props: route => ({p: parseInt(route.query.p) || 1})},
    {path: '/create', component: Editor, props: route => ({product_id: 0})},
    {path: '/:id', component: Editor, props: route => ({product_id: parseInt(route.params.id)})}
];

export default new VueRouter({
    routes,
    mode: "history"
});
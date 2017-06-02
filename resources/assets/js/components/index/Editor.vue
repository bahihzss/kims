<template>
    <div class="container editor">
        <div class="row">
            <div class="col-lg-2">
                <div class="panel panel-primary editor__control">
                    <div class="panel-heading">
                        コントロール
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary" @click="save" :disabled="!edited">保存</button>
                                <button v-show="!!product_id" class="btn btn-danger" @click="destroy">削除</button>
                                <router-link class="btn btn-default" :to="'/' + prev_id"
                                             v-show="!!product_id" :class="[{disabled: !prev_id}]">←
                                </router-link>
                                <router-link class="btn btn-default" :to="'/' + next_id"
                                             v-show="!!product_id" :class="[{disabled: !next_id}]">→
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 editor__information">
                <ol class="breadcrumb">
                    <li><a href="/">楽器一覧</a></li>
                    <li class="active" v-if="!!product_id">{{ current.name }}</li>
                    <li class="active" v-else>新規作成</li>
                </ol>
                <div class="panel panel-success">
                    <div class="panel-heading">楽器情報</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-2"><label>ID</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="text" :value="product.id"
                                                          readonly></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>商品名</label></div>
                            <div class="col-sm-10">
                                <Autocomplete v-model="product.name" name="name"></Autocomplete>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>仕入日</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="date"
                                                          v-model="product.purchase_date"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>仕入価格</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="number"
                                                          v-model="product.purchase_price"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>仕入元</label></div>
                            <div class="col-sm-10">
                                <Autocomplete v-model="product.purchase_place" name="purchase"></Autocomplete>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>販売日</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="date"
                                                          v-model="product.sale_date"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>販売価格</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="number"
                                                          v-model="product.sale_price"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>販売先</label></div>
                            <div class="col-sm-10">
                                <Autocomplete v-model="product.sale_place" name="sale"></Autocomplete>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>分類</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="number"
                                                          v-model="product.category_id"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2"><label>出品日</label></div>
                            <div class="col-sm-10"><input class="form-control input-sm" type="date"
                                                          v-model="product.exhibition_date"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .editor__information .row {
        margin-bottom: 10px;
    }
</style>

<script>
    const equal = require('equals');
    const clone = require('clone');
    import Autocomplete from './Autocomplete.vue'

    export default {
        components: {Autocomplete},
        props: {
            "product_id": {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                product: {},
                current: {},
                next_id: 0,
                prev_id: 0,
                defaultColumn: {
                    "name": "",
                    "purchase_date": null,
                    "purchase_price": null,
                    "purchase_place": "",
                    "sale_date": null,
                    "sale_price": null,
                    "sale_place": "",
                    "category_id": 1,
                    "exhibition_date": null
                }
            }
        },
        mounted() {
            this.fetch();
        },
        computed: {
            edited() {
                return !equal(this.current, this.product);
            }
        },
        watch: {
            product_id() {
                this.fetch();
            }
        },
        methods: {
            fetch() {
                if (this.product_id === 0) {
                    this.product = clone(this.defaultColumn);
                    this.current = clone(this.defaultColumn);
                    this.next_id = null;
                    this.prev_id = null;
                } else {
                    this.$http.get('/api/product/' + this.product_id + '/edit')
                        .then(response => {
                            this.product = response.data.product;
                            this.current = clone(response.data.product);
                            this.next_id = response.data.next;
                            this.prev_id = response.data.prev;
                        });
                }
            },
            save() {
                if (this.product_id === 0) {
                    this.$http.post('/api/product', this.product)
                        .then(response => this.$router.replace('/'));
                } else {
                    this.$http.put('/api/product/' + this.product_id, this.product)
                        .then(response => this.current = this.product);
                }
            },
            destroy() {
                this.$http.delete('/api/product/' + this.product_id)
                    .then(response => this.$router.replace('/'))
            }
        }
    }
</script>
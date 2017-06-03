<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">検索条件</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-1"><label>仕入日</label></div>
                            <div class="col-lg-12 col-sm-5">
                                <input
                                        type="date"
                                        class="form-control input-sm"
                                        v-model="filter.purchase_date_start"
                                >
                            </div>
                            <div class="col-lg-12 col-sm-1">～</div>
                            <div class="col-lg-12 col-sm-5">
                                <input
                                        type="date"
                                        class="form-control input-sm"
                                        v-model="filter.purchase_date_end"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 15px">
                                    <button
                                            class="btn btn-sm btn-default"
                                            :class="[{'active':filter.only_stock}]"
                                            @click="changeStock"
                                    > 在庫のみ表示</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" style="width: 100%;margin-bottom: 20px" @click="$router.push('/create')">新規作成</button>
            </div>
            <div class="col-lg-10">
                <Pagination :pagination="products"></Pagination>
                <div class="panel panel-success">
                    <div class="panel-heading">楽器一覧</div>
                    <table class="table table-hover index__table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width: 40%">商品名</th>
                            <th>仕入日</th>
                            <th>仕入価格</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="index__table__row" :class="typeColor(product)" v-for="product in products.data"
                            @click="$router.push('/'+product.id)">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.purchase_date }}</td>
                            <td>{{ product.purchase_price }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .index__table__row {
        cursor: pointer;
    }
</style>

<script>
    import Pagination from './Pagination.vue'

    export default {
        components: {
            Pagination
        },
        props: {
            p: {
                type: Number,
                default: 1
            }
        },
        data() {
            return {
                products: {},
                filter: {
                    purchase_date_start: "",
                    purchase_date_end: "",
                    only_stock: false
                }
            }
        },
        watch: {
            p() {
                this.fetch();
            },
            filter: {
                handler() {
                    this.requery();
                },
                deep: true
            },
         },
        mounted() {
            this.fetch();
        },
        methods: {
            fetch() {
                let params = {
                    p: this.p,
                    purchase_date_start: this.filter.purchase_date_start,
                    purchase_date_end: this.filter.purchase_date_end,
                    only_stock: this.filter.only_stock ? 1 : 0
                };
                this.$http.get('/api/product', {params})
                    .then(response => this.products = response.data);
            },
            changeStock() {
                this.filter.only_stock=!this.filter.only_stock;
                this.requery();
            },
            requery() {
                this.$router.push('?p=1');
                this.fetch();
            },
            typeColor(product) {
                if (product.category_id === 8) {
                    return "warning"
                } else if (!((product.category_id === 1 || product.category_id === 2) && product.sale_date===null)) {
                    return "active"
                }
            }
        }
    }
</script>
<template>
    <ul class="pagination home-pagination">
        <li v-for="page in pageList" :class="[{'disabled': isDisabled(page)}, {'active': isActive(page)}]">
            <router-link :to="url+'?p='+page">{{ page }}</router-link>
        </li>
    </ul>
</template>

<style>
    .home-pagination > li > span {
        cursor: pointer;
    }

    .home-pagination {
        padding: 0;
        margin: 0;
    }
</style>

<script>
    export default {
        props: {
            pagination: {
                type: Object,
                default() {
                    return {}
                }
            },
            url: {
                type: String,
                default: ""
            }
        },
        computed: {
            isFirst() {
                return this.pagination.current_page <= 1
            },
            isLast() {
                return this.pagination.current_page >= this.pagination.last_page
            },
            pageList() {
                if (this.pagination.last_page <= 12) {
                    let result = [];
                    for (let i = 1; i <= this.pagination.last_page; i++) {
                        result.push(i);
                    }
                    return result;
                } else if (this.pagination.current_page <= 7) {
                    return [1, 2, 3, 4, 5, 6, 7, 8, '...', this.pagination.last_page - 2, this.pagination.last_page - 1, this.pagination.last_page];
                } else if (this.pagination.current_page > this.pagination.last_page - 7) {
                    let result = [1, 2, 3, '...'];
                    for (let i = 7; i >= 0; i--) {
                        result.push(this.pagination.last_page - i);
                    }
                    return result;
                } else {
                    let result = [1, 2, 3, '...'];
                    for (let i = -3; i <= 3; i++) {
                        result.push(this.pagination.current_page + i);
                    }
                    return result.concat(['...', this.pagination.last_page-2, this.pagination.last_page-1, this.pagination.last_page]);
                }
            }
        },
        methods: {
            isDisabled(page) {
                return page === '...';
            },
            isActive(page) {
                return page === this.pagination.current_page;
            }
        }
    }
</script>
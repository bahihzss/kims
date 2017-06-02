<template>
    <span>
        <datalist :id="uuid">
            <option v-for="option in options" :value="option">{{ option }}</option>
        </datalist>
        <input type="text" :list="uuid" :name="name" v-model="inputValue" class="form-control input-sm" @input="queryChange">
    </span>
</template>

<script>
    const uuidV1 = require('uuid/v1');

    export default {
        props: {
            value: {
                type: String,
                default: ""
            },
            name: {
                type: String,
                default: ""
            }
        },
        data() {
            return {
                uuid: uuidV1(),
                options: [],
                inputValue: this.value
            }
        },
        mounted() {
            this.queryChange('');
        },
        methods: {
            queryChange() {
                this.$http.get('/api/autocomplete/' + this.name + '?text=' + this.inputValue)
                    .then(response => this.options = response.data);
                this.$emit('input', this.inputValue);
            }
        }
    }
</script>
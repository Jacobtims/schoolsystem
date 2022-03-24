<template>
    <div class="d-flex flex-row align-items-center justify-content-center" id="pagination">
        <button class="btn" :disabled="noPreviousPage" @click="loadPage(1)">
            <i class="fa-solid fa-angles-left"></i>
        </button>
        <button class="btn" :disabled="noPreviousPage" @click="loadPage(this.pagination.current_page - 1)"><i
            class="fa-solid fa-angle-left"></i>
        </button>
        <input class="btn" type="number" v-model="page" @keydown.enter="loadPage(page)" :style="{'width': 'calc('+(page.toString().length ?? 1)+'ch + 26px) !important'}"/>
        <span>of {{ this.pagination.last_page }}</span>
        <button class="btn" :disabled="noNextPage" @click="loadPage(this.pagination.current_page + 1)"><i
            class="fa-solid fa-angle-right"></i>
        </button>
        <button class="btn" :disabled="noNextPage" @click="loadPage(this.pagination.last_page)"><i
            class="fa-solid fa-angles-right"></i>
        </button>
    </div>
</template>
<script>
export default {
    name: 'Pagination',
    props: {
        pagination: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            page: this.pagination.current_page
        }
    },
    watch: {
        'pagination.current_page': function (page) {
            this.page = page;
        }
    },
    methods: {
        loadPage(page) {
            this.$inertia.get(this.$page.url, {page: page}, {
                preserveState: true
            });
        }
    },
    computed: {
        noPreviousPage() {
            return this.pagination.current_page - 1 <= 0;
        },
        noNextPage() {
            return this.pagination.current_page + 1 > this.pagination.last_page;
        }
    }
}
</script>
<style lang="scss">
#pagination {
    vertical-align: middle;

    .btn {
        background-color: #fff;
        color: #055CFC;
        border: 1px solid #E5E5E5;

        &:not(:last-child) {
            margin-right: 8px;
        }

        i {
            font-size: 14px;
        }
    }

    input[type=number] {
        color: #055CFC;
        border: 1px solid #E5E5E5;
        background: #fff;
        padding: 6px 12px;
        width: max-content;

        &:focus {
            outline: none;
        }
        -moz-appearance: textfield;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    span {
        margin-right: 8px;
    }
}
</style>

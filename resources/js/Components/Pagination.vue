<template>
    <div class="flex items-center justify-center gap-1.5" id="pagination">
        <Button btn="secondary" size="sm" :disabled="noPreviousPage" @click="loadPage(1)">
            <i class="fa-solid fa-angles-left"></i>
        </Button>
        <Button btn="secondary" size="sm" :disabled="noPreviousPage"
                @click="loadPage(this.pagination.current_page - 1)">
            <i class="fa-solid fa-angle-left"></i>
        </Button>

        <Input type="number" v-model="page" @keydown.enter="loadPage(page)"
               :style="{'width': 'calc('+(page.toString().length ?? 1)+'ch + 26px) !important'}"/>
        <span class="whitespace-nowrap">of {{ this.pagination.last_page }}</span>

        <Button btn="secondary" size="sm" :disabled="noNextPage" @click="loadPage(this.pagination.current_page + 1)">
            <i class="fa-solid fa-angle-right"></i>
        </Button>
        <Button btn="secondary" size="sm" :disabled="noNextPage" @click="loadPage(this.pagination.last_page)">
            <i class="fa-solid fa-angles-right"></i>
        </Button>
    </div>
</template>
<script>
import Button from "@/Components/Button.vue";
import Input from "@/Components/Inputs/Input.vue";

export default {
    name: 'Pagination',
    components: {
        Input,
        Button
    },
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
    input[type=number] {
        @apply border-gray-200;
        padding-top: 7px;
        padding-bottom: 7px;

        &:focus {
            @apply focus:border-gray-300 focus:ring-0;
        }

        -moz-appearance: textfield;
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
}
</style>

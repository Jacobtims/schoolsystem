<template>
    <button class="px-4 py-2.5 rounded-lg border border-transparent shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-75" :class="styleClasses" :disabled="disabled">
        <slot/>
    </button>
</template>
<script>
export default {
    props: {
        disabled: {
            default: false,
            type: Boolean
        },
        btn: {
            default: 'primary',
            type: String
        }
    },
    computed: {
        styleClasses() {
            switch (this.btn) {
                case 'primary':
                    return 'text-white bg-primary-700 hover:bg-primary-800 focus:ring-primary-600 disabled:hover:bg-primary-700';
            }
        }
    }
}
</script>

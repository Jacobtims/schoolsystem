<template>
    <div class="mb-4 p-4 text-sm rounded-lg" :class="styleClasses" role="alert">
        <slot/>
    </div>
</template>
<script>
export default {
    props: {
        type: {
            default: 'success',
            type: String
        }
    },
    computed: {
        styleClasses() {
            switch (this.type) {
                case 'info':
                    return 'text-blue-700 bg-blue-100';
                case 'success':
                    return 'text-green-800 bg-green-100';
                case 'danger':
                    return 'text-red-800 bg-red-100';
            }
        }
    }
}
</script>

<template>
    <Dialog v-model:visible="open" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
        :header="header" :draggable="false" :modal="true" @hide="$emit('close')">
        <div>
            <slot/>
        </div>
    </Dialog>
</template>

<script>
import Dialog from "primevue/dialog";

export default {
    components: {
        Dialog
    },
    props: {
        open: Boolean,
        header: String
    }
}
</script>

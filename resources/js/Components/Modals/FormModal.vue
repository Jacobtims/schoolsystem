<template>
    <Dialog v-model:visible="open" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
        :header="header" :draggable="false" :modal="true" @hide="$emit('close')">
        <div>
            <slot/>
        </div>

        <template #footer>
            <button class="btn btn-primary" type="submit" @click="$emit('action')" autofocus :disabled="disabled">
                <i class="fa-solid fa-check"></i> {{ submitText }}</button>
            <button class="btn btn-secondary" @click="$emit('close')" :disabled="disabled">
                <i class="fa-solid fa-xmark"></i> {{ cancelText }}</button>
        </template>
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
        header: String,
        disabled: Boolean,
        submitText: String,
        cancelText: {
            default: 'Annuleren',
            type: String,
        }
    }
}
</script>

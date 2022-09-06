<template>
    <Dialog header="Weet je het zeker?" v-model:visible="open" :style="{width: '400px'}" :modal="true"
            :draggable="false" @hide="$emit('close')">
        <p>
            <slot/>
        </p>

        <template #footer>
            <button class="btn btn-danger" @click="$emit('delete')"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="$emit('close')"><i class="fa-solid fa-xmark"></i> Annuleren</button>
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
        open: Boolean
    }
}
</script>

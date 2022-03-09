<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true" :draggable="false" @hide="close">
        <p>Weet je zeker dat je dit vak wilt verwijderen?</p>
        <template #footer>
            <button class="btn btn-danger" @click="deleteSubject"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'DeleteSubjectConfirmationModal',
    props: {
        openModal: Boolean,
        subjectId: Number
    },
    components: {
        Dialog
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
            this.$parent.deleteId = null;
        },
        deleteSubject() {
            this.$inertia.delete(route('admin.subjects.destroy', this.subjectId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                },
            })
        }
    }
}
</script>

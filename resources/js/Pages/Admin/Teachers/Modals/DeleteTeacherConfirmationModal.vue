<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true" :draggable="false" @hide="close">
        <p>Weet je zeker dat je deze docent wilt verwijderen?</p>
        <template #footer>
            <button class="btn btn-danger" @click="deleteTeacher"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'DeleteTeacherConfirmationModal',
    props: {
        openModal: Boolean,
        userId: Number
    },
    components: {
        Dialog
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
            this.$parent.deleteId = null;
        },
        deleteTeacher() {
            this.$inertia.delete(route('admin.teachers.destroy', this.userId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                },
            })
        }
    }
}
</script>

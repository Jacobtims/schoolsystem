<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true" :draggable="false" @hide="close">
        <p>Weet je zeker dat je de geselecteerde docenten wilt verwijderen?</p>
        <template #footer>
            <button class="btn btn-danger" @click="deleteTeachers"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'DeleteSelectedTeachersConfirmationModal',
    props: {
        openModal: Boolean,
        userIds: Array
    },
    components: {
        Dialog
    },
    methods: {
        close() {
            this.$parent.openSelectedDeleteModal = false;
        },
        deleteTeachers() {
            this.userIds.forEach(function (id) {
                this.$inertia.delete(route('admin.students.destroy', id), {
                    preserveScroll: true
                })
            }.bind(this));
            this.$parent.selectedRows = [];
            this.close();
        }
    }
}
</script>

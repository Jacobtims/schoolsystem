<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true" :draggable="false" @hide="close">
        <p>Weet je zeker dat je dit lokaal wilt verwijderen?</p>
        <template #footer>
            <button class="btn btn-danger" @click="deleteClassroom"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'DeleteClassroomConfirmationModal',
    props: {
        openModal: Boolean,
        classroomId: Number
    },
    components: {
        Dialog
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
            this.$parent.deleteId = null;
        },
        deleteClassroom() {
            this.$inertia.delete(route('admin.classrooms.destroy', this.classroomId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol verwijderd!', 'Lokaal is verwijderd.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het verwijderen van dit lokaal. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteClassroom">
        Weet je zeker dat je dit lokaal wilt verwijderen?
    </DeleteModal>
</template>
<script>
import Dialog from "primevue/dialog";
import DeleteModal from "@/Components/Modals/DeleteModal.vue";
export default {
    name: 'DeleteClassroomConfirmationModal',
    props: {
        openModal: Boolean,
        classroomId: Number
    },
    components: {
        DeleteModal,
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

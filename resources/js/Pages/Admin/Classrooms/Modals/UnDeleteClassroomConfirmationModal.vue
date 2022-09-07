<template>
    <DeleteModal :open="openModal" @close="close" @delete="restoreClassroom">
        Weet je zeker dat je dit lokaal weer wilt toevoegen?
    </DeleteModal>
</template>
<script>
import Dialog from "primevue/dialog";
import DeleteModal from "@/Components/Modals/DeleteModal.vue";
export default {
    name: 'UnDeleteClassroomConfirmationModal',
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
            this.$parent.openUnDeleteModal = false;
            this.$parent.unDeleteId = null;
        },
        restoreClassroom() {
            this.$inertia.post(route('admin.classrooms.restore', this.classroomId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol toegevoegd!', 'Lokaal is weer toegevoegd.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het toevoegen van dit lokaal. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

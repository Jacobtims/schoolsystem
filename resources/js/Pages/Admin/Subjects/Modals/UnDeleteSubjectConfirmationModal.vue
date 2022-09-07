<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteSubject">
        Weet je zeker dat je dit vak weer wilt toevoegen?
    </DeleteModal>
</template>
<script>
import DeleteModal from "@/components/Modals/DeleteModal.vue";

export default {
    name: 'UnDeleteSubjectConfirmationModal',
    props: {
        openModal: Boolean,
        subjectId: Number
    },
    components: {
        DeleteModal
    },
    methods: {
        close() {
            this.$parent.openUnDeleteModal = false;
            this.$parent.unDeleteId = null;
        },
        deleteSubject() {
            this.$inertia.post(route('admin.subjects.restore', this.subjectId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol toegevoegd!', 'Vak is weer toegevoegd.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het toevoegen van dit vak. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

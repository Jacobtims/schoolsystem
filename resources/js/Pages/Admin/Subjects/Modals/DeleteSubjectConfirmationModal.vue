<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteSubject">
        Weet je zeker dat je dit vak wilt verwijderen?
    </DeleteModal>
</template>
<script>
import DeleteModal from "@/components/Modals/DeleteModal.vue";

export default {
    name: 'DeleteSubjectConfirmationModal',
    props: {
        openModal: Boolean,
        subjectId: Number
    },
    components: {
        DeleteModal
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
                    this.toast('success', 'Succesvol verwijderd!', 'Vak is verwijderd.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het verwijderen van dit vak. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

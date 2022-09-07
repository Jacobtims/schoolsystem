<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteStudent">
        Weet je zeker dat je deze student wilt verwijderen?
    </DeleteModal>
</template>
<script>
import DeleteModal from "@/Components/Modals/DeleteModal.vue";

export default {
    name: 'DeleteStudentConfirmationModal',
    props: {
        openModal: Boolean,
        userId: Number
    },
    components: {
        DeleteModal
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
            this.$parent.deleteId = null;
        },
        deleteStudent() {
            this.$inertia.delete(route('admin.students.destroy', this.userId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol verwijderd!', 'Student is verwijderd.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het verwijderen van deze student. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

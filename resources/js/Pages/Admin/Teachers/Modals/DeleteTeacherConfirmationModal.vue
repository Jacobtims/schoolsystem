<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteTeacher">
        Weet je zeker dat je deze docent wilt verwijderen?
    </DeleteModal>
</template>
<script>
import DeleteModal from "@/Components/Modals/DeleteModal.vue";

export default {
    name: 'DeleteTeacherConfirmationModal',
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
        deleteTeacher() {
            this.$inertia.delete(route('admin.teachers.destroy', this.userId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol verwijderd!', 'Docent is verwijderd.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het verwijderen van deze docent. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

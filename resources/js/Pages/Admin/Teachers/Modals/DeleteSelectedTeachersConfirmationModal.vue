<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteTeachers">
        Weet je zeker dat je de geselecteerde docenten wilt verwijderen?
    </DeleteModal>
</template>
<script>
import DeleteModal from "@/Components/Modals/DeleteModal.vue";

export default {
    name: 'DeleteSelectedTeachersConfirmationModal',
    props: {
        openModal: Boolean,
        userIds: Array
    },
    components: {
        DeleteModal
    },
    methods: {
        close() {
            this.$parent.openSelectedDeleteModal = false;
        },
        deleteTeachers() {
            this.userIds.forEach(function (id) {
                this.$inertia.delete(route('admin.teachers.destroy', id), {
                    preserveScroll: true
                })
            }.bind(this));
            this.$parent.selectedRows = [];
            this.close();
        }
    }
}
</script>

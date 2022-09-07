<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteStudents">
        Weet je zeker dat je de geselecteerde studenten wilt verwijderen?
    </DeleteModal>
</template>
<script>
import DeleteModal from "@/Components/Modals/DeleteModal.vue";

export default {
    name: 'DeleteSelectedStudentsConfirmationModal',
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
        deleteStudents() {
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

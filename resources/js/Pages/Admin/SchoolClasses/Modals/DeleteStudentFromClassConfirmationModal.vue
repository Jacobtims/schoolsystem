<template>
    <DeleteModal :open="openModal" @close="close" @delete="deleteStudent">
        Weet je zeker dat je deze student uit klas <strong>{{ schoolClass.name }}</strong> wilt verwijderen?
    </DeleteModal>
</template>
<script>
import Dialog from "primevue/dialog";
import DeleteModal from "@/components/Modals/DeleteModal.vue";

export default {
    name: 'DeleteStudentFromClassConfirmationModal',
    props: {
        openModal: Boolean,
        schoolClass: Object,
        studentId: Number
    },
    components: {
        DeleteModal,
        Dialog
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
            this.$parent.selectedStudent = null;
        },
        deleteStudent() {
            this.$inertia.post(route('admin.classes.removeStudent', [this.studentId]), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol verwijderd!', 'Student is verwijderd uit deze klas.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het verwijderen van deze student. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

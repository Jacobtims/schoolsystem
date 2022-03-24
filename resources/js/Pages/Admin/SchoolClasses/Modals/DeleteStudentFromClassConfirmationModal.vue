<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true"
            :draggable="false" @hide="close" v-if="schoolClass && studentId">
        <p>Weet je zeker dat je deze student uit klas <strong>{{ schoolClass.name }}</strong> wilt verwijderen?</p>

        <template #footer>
            <button class="btn btn-danger" @click="deleteStudent"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";

export default {
    name: 'DeleteStudentFromClassConfirmationModal',
    props: {
        openModal: Boolean,
        schoolClass: Object,
        studentId: Number
    },
    components: {
        Dialog
    },
    methods: {
        close() {
            this.$parent.openDeleteModal = false;
            this.$parent.selectedStudent = null;
        },
        deleteStudent() {
            this.$inertia.post(route('admin.classes.removeStudent', [this.schoolClass.id, this.studentId]), {}, {
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

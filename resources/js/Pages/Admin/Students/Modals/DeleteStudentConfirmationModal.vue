<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true" :draggable="false" @hide="close">
        <p>Weet je zeker dat je deze student wilt verwijderen?</p>
        <template #footer>
            <button class="btn btn-danger" @click="deleteStudent"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'DeleteStudentConfirmationModal',
    props: {
        openModal: Boolean,
        userId: Number
    },
    components: {
        Dialog
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

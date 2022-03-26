<template>
    <Dialog header="Weet je het zeker?" v-model:visible="openModal" :style="{width: '400px'}" :modal="true" :draggable="false" @hide="close">
        <p>Weet je zeker dat je dit vak weer wilt toevoegen?</p>
        <template #footer>
            <button class="btn btn-success" @click="deleteSubject"><i class="fa-solid fa-check"></i> Ja</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'UnDeleteSubjectConfirmationModal',
    props: {
        openModal: Boolean,
        subjectId: Number
    },
    components: {
        Dialog
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

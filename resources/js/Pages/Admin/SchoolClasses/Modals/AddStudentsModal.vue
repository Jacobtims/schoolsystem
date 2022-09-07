<template>
    <FormModal :open="openModal" :header="'Studenten toevoegen aan ' + schoolClass.name" submit-text="Toevoegen"
               @close="close" @action="addStudentsToClass" :disabled="disabled" v-if="schoolClass">
        <!-- List with student ids -->
        <h5>Leerlingnummer</h5>
        <div class="d-flex mb-3" v-for="(id, index) in studentIds" :key="'studentid'+index">
            <input class="form-control me-2" type="number" v-model="studentIds[index]"/>
            <button class="btn btn-outline-danger" @click="removeId(index)" :disabled="studentIds.length <= 1">
                <i class="fa-solid fa-trash-can"></i></button>
        </div>
        <div class="d-flex">
            <button class="btn btn-sm btn-outline-success me-3" @click="addIdRow">
                <i class="fa-solid fa-plus"></i> Nieuwe toevoegen
            </button>
            <button class="btn btn-sm btn-outline-primary" @click="addMultiple">Meerdere toevoegen</button>
        </div>

        <!-- Add multiple modal -->
        <FormModal :open="showMultipleModal" submit-text="Studenten toevoegen"
                   @close="closeMultipleModal" @action="addMultipleIds">
            <h5 class="mb-0">Leerlingnummers</h5>
            <small>Gescheiden door komma's (,)</small>
            <textarea class="form-control" rows="5" v-model="multipleIds" placeholder="Voorbeeld: 1,2,3,4,5"></textarea>
        </FormModal>

    </FormModal>
</template>
<script>
import Dialog from "primevue/dialog";
import FormModal from "@/components/Modals/FormModal.vue";

export default {
    name: 'AddStudentsModal',
    props: {
        openModal: Boolean,
        schoolClass: Object
    },
    components: {
        FormModal,
        Dialog
    },
    data() {
        return {
            studentIds: [null],
            showMultipleModal: false,
            multipleIds: null,
            disabled: false
        }
    },
    methods: {
        close() {
            this.$parent.openAddStudentsModal = false;
            this.studentIds = [null]
        },
        removeId(id) {
            this.studentIds.splice(id, 1);
        },
        addIdRow() {
            this.studentIds.push(null);
        },
        addMultiple() {
            this.showMultipleModal = true;
        },
        closeMultipleModal() {
            this.showMultipleModal = false;
            this.multipleIds = null;
        },
        addMultipleIds() {
            const idsArray = this.multipleIds.split(",");
            idsArray.forEach((id) => {
                this.studentIds.push(id.replace(/ /g, '')); // Remove white spaces and push to studentsIds array
            });
            this.closeMultipleModal();
        },
        addStudentsToClass() {
            this.$inertia.post(route('admin.classes.addStudents', this.schoolClass.id), {
                studentIds: this.studentIds
            }, {
                preserveScroll: true,
                onBefore: () => {
                    this.disabled = true;
                },
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol toegevoegd!', 'Studenten zijn toegevoegd aan deze klas.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het toevoegen van deze studenten. Probeer het opnieuw.')
                },
                onFinish: () => {
                    this.disabled = false;
                }
            })
        }
    }
}
</script>

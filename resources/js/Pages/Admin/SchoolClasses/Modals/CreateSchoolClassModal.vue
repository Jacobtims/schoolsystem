<template>
    <FormModal :open="openModal" header="Nieuwe klas aanmaken" submit-text="Aanmaken"
               @close="close" @action="createClass" :disabled="disabled">
        <!-- School class information -->
        <h6>Klas informatie</h6>
        <form class="row g-3" @submit.prevent="createClass">
            <div class="col-md-6">
                <Input label="Klas naam" v-model="className" :error="$page.props.errors.class_name">
                    <div class="form-text">Let op! Deze moet uniek zijn.</div>
                </Input>
            </div>
            <div class="col-md-6">
                <Input label="Afkorting mentor" v-model="mentorAbbreviation" :error="$page.props.errors.mentor_abbreviation">
                    <div class="form-text">Dit is de afkorting van de docent.</div>
                </Input>
            </div>
        </form>

        <!-- All class students -->
        <div class="mt-3">
            <h6>Leerlingen</h6>
            <div class="d-flex mb-3" v-for="(id, index) in studentIds" :key="'studentid'+index">
                <input class="form-control me-2" type="number" v-model="studentIds[index]" placeholder="Leerlingnummer"/>
                <button class="btn btn-outline-danger" @click="removeId(index)" :disabled="studentIds.length <= 1">
                    <i class="fa-solid fa-trash-can"></i></button>
            </div>
            <div class="d-flex">
                <button class="btn btn-sm btn-outline-success me-3" @click="addIdRow">
                    <i class="fa-solid fa-plus"></i> Nieuwe toevoegen
                </button>
                <button class="btn btn-sm btn-outline-primary" @click="addMultiple">Meerdere toevoegen</button>
            </div>
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
import FormModal from "@/components/Modals/FormModal.vue";
import Input from "@/components/Inputs/Input.vue";

export default {
    name: 'CreateSchoolClassModal',
    props: {
        openModal: Boolean
    },
    components: {
        Input,
        FormModal
    },
    data() {
        return {
            className: null,
            mentorAbbreviation: null,
            studentIds: [null],
            showMultipleModal: false,
            multipleIds: null,
            disabled: false
        }
    },
    methods: {
        close() {
            this.$parent.openCreateModal = false;
            this.studentIds = [null];
            this.className = null;
            this.mentorAbbreviation = null;
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
        createClass() {
            this.$inertia.post(route('admin.classes.store'), {
                class_name: this.className,
                mentor_abbreviation: this.mentorAbbreviation,
                studentIds: this.studentIds
            }, {
                preserveScroll: true,
                onBefore: () => {
                    this.disabled = true;
                },
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', 'Klas is aangemaakt.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van deze klas. Probeer het opnieuw.')
                },
                onFinish: () => {
                    this.disabled = false;
                }
            })
        },
    }
}
</script>

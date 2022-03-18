<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}"
            :style="{width: '40vw'}" header="Nieuwe klas aanmaken" :draggable="false" :modal="true" @hide="close">
        <div>
            <h6>Klas informatie</h6>
            <form class="row g-3" @submit.prevent="createClass">
                <div class="col-md-6">
                    <label for="inputClassName" class="form-label">Klas naam</label><br/>
                    <input type="text" class="form-control" :class="{'is-invalid': $page.props.errors.class_name}" id="inputClassName" aria-describedby="classNameHelp" v-model="className">
                    <div id="classNameHelp" class="form-text">Let op! Deze moet uniek zijn.</div>
                    <div id="feedbackClassName" class="invalid-feedback" v-if="$page.props.errors.class_name">
                        {{ $page.props.errors.class_name }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputMentorAbbreviation" class="form-label">Afkorting mentor</label><br/>
                    <input type="text" class="form-control" :class="{'is-invalid': $page.props.errors.mentor_abbreviation}" id="inputMentorAbbreviation" aria-describedby="mentorAbbreviationHelp" v-model="mentorAbbreviation">
                    <div id="mentorAbbreviationHelp" class="form-text">Dit is de afkorting van de docent.</div>
                    <div id="feedbackMentorAbbreviation" class="invalid-feedback" v-if="$page.props.errors.mentor_abbreviation">
                        {{ $page.props.errors.mentor_abbreviation }}
                    </div>
                </div>
            </form>


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
            <Dialog v-model:visible="showMultipleModal" :breakpoints="{'1200px': '48vw', '992px': '61vw'}"
                    :style="{width: '35vw'}" :modal="true" :draggable="false" @hide="closeMultipleModal">
                <h5 class="mb-0">Leerlingnummers</h5>
                <small>Gescheiden door komma's (,)</small>
                <textarea class="form-control" rows="5" v-model="multipleIds" placeholder="Voorbeeld: 1,2,3,4,5"></textarea>

                <template #footer>
                    <button class="btn btn-sm btn-success" @click="addMultipleIds">
                        <i class="fa-solid fa-check"></i> Studenten toevoegen
                    </button>
                    <button class="btn btn-sm btn-secondary" @click="closeMultipleModal">
                        <i class="fa-solid fa-xmark"></i> Annuleren
                    </button>
                </template>
            </Dialog>
        </div>

        <template #footer>
            <button class="btn btn-primary" type="submit" autofocus @click="createClass"><i class="fa-solid fa-check"></i> Aanmaken</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";

export default {
    name: 'CreateSchoolClassModal',
    props: {
        openModal: Boolean
    },
    components: {
        Dialog
    },
    data() {
        return {
            className: null,
            mentorAbbreviation: null,
            studentIds: [null],
            showMultipleModal: false,
            multipleIds: null
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
                onSuccess: () => {
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', 'Klas is aangemaakt.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van deze klas. Probeer het opnieuw.')
                }
            })
        },
    }
}
</script>

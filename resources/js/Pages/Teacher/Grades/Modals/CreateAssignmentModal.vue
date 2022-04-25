<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Nieuwe toets aanmaken" :draggable="false" :modal="true" @hide="close">
        <div>
            <form class="row g-3" @submit.prevent="createAssignment()">
                <div class="col-md-6">
                    <label for="inputSubject" class="form-label">Vak</label>
                    <input type="text" class="form-control" id="inputSubject" readonly :value="subject">
                </div>
                <div class="col-md-6">
                    <label for="inputSchoolClass" class="form-label">Klas</label>
                    <input type="text" class="form-control" id="inputSchoolClass" readonly :value="schoolClass">
                </div>
                <div class="col-md-8">
                    <label for="inputName" class="form-label">Naam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': assignmentForm.errors.name}"
                           id="inputName" v-model="assignmentForm.name" aria-describedby="feedbackName"
                           required min="2">
                    <div id="feedbackName" class="invalid-feedback" v-if="assignmentForm.errors.name">
                        {{ assignmentForm.errors.name }}
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="inputWeighting" class="form-label">Weging</label>
                    <input type="number" class="form-control" :class="{'is-invalid': assignmentForm.errors.weighting}"
                           id="inputWeighting" v-model="assignmentForm.weighting" aria-describedby="feedbackWeighting"
                           required min="1">
                    <div id="feedbackWeighting" class="invalid-feedback" v-if="assignmentForm.errors.weighting">
                        {{ assignmentForm.errors.weighting }}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputDescription" class="form-label">Omschrijving</label>
                    <textarea class="form-control" :class="{'is-invalid': assignmentForm.errors.description}"
                              id="inputDescription" v-model="assignmentForm.description" aria-describedby="feedbackDescription" rows="3" required></textarea>
                    <div id="feedbackDescription" class="invalid-feedback" v-if="assignmentForm.errors.description">
                        {{ assignmentForm.errors.description }}
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" @click="createAssignment()" autofocus :disabled="assignmentForm.processing"><i class="fa-solid fa-check"></i> Aanmaken</button>
            <button class="btn btn-outline-secondary" @click="close" :disabled="assignmentForm.processing"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: 'CreateAssignmentModal',
    props: {
        openModal: Boolean,
        subject: String,
        schoolClass: String
    },
    components: {
        Dialog
    },
    data() {
        return {
            assignmentForm: useForm({
                subject: null,
                school_class: null,
                name: null,
                weighting: 1,
                description: null,
            })
        }
    },
    methods: {
        close() {
            this.$parent.openCreateModal = false;
        },
        createAssignment() {
            // Set subject & school class
            this.assignmentForm.subject = this.subject;
            this.assignmentForm.school_class = this.schoolClass;
            // Post request
            this.assignmentForm.post(route('teacher.grades.assignment.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.assignmentForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', '')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van de toets. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

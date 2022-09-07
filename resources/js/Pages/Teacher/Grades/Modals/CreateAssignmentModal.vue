<template>
    <FormModal :open="openModal" header="Nieuwe toets aanmaken" submit-text="Aanmaken"
               @close="close" @action="createAssignment" :disabled="assignmentForm.processing">
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
                <Input label="Naam" v-model="assignmentForm.name" :error="assignmentForm.errors.name" min="2" required/>
            </div>
            <div class="col-md-4">
                <Input label="Weging" v-model="assignmentForm.weighting" :error="assignmentForm.errors.weighting" min="1" required/>
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
    </FormModal>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/Components/Modals/FormModal.vue";
import Input from "@/Components/Inputs/Input.vue";

export default {
    name: 'CreateAssignmentModal',
    props: {
        openModal: Boolean,
        subject: String,
        schoolClass: String
    },
    components: {
        Input,
        FormModal,
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

<template>
    <FormModal :open="openModal" header="Huiswerk toevoegen" :disabled="homeworkForm.processing" submit-text="Toevoegen"
               @action="addHomework" @close="close">
        <form class="row g-3" @submit.prevent="addHomework">
            <div class="col-md-12">
                <label for="inputType" class="form-label">Type</label>
                <select class="form-select" :class="{'is-invalid': homeworkForm.errors.type}" id="inputType"
                        v-model="homeworkForm.type" aria-describedby="feedbackType" required>
                    <option value="homework">Huiswerk</option>
                    <option value="test">Toets</option>
                    <option value="activity">Activiteit</option>
                </select>

                <div id="feedbackType" class="invalid-feedback" v-if="homeworkForm.errors.type">
                    {{ homeworkForm.errors.type }}
                </div>
            </div>
            <div class="col-md-12">
                <label for="inputDescription" class="form-label">Omschrijving</label>
                <textarea type="number" class="form-control" :class="{'is-invalid': homeworkForm.errors.description}"
                          id="inputDescription" v-model="homeworkForm.description" aria-describedby="feedbackDescription"
                          required min="1" rows="3"></textarea>
                <div id="feedbackDescription" class="invalid-feedback" v-if="homeworkForm.errors.description">
                    {{ homeworkForm.errors.description }}
                </div>
            </div>
        </form>
    </FormModal>
</template>
<script>
import ShowModal from "@/Components/Modals/ShowModal.vue";
import FormModal from "@/Components/Modals/FormModal.vue";
import {useForm} from "@inertiajs/inertia-vue3";
export default {
    components: {FormModal, ShowModal},
    props: {
        openModal: Boolean,
        lessonId: [Number, String]
    },
    data() {
        return {
            homeworkForm: useForm({
                lesson_id: null,
                type: "homework",
                description: null
            })
        }
    },
    methods: {
        close() {
            this.$parent.openHwModal = false;
        },
        addHomework() {
            this.homeworkForm.lesson_id = this.lessonId;

            this.homeworkForm.post(this.route('teacher.schedules.addHomework'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                    this.$emit('getLesson');
                    this.homeworkForm.reset();
                    this.toast('success', 'Huiswerk is toegevoegd!')
                },
                onError: () => {
                    this.toast('error', 'Er is iets fout gegaan bij het toevoegen van het huiswerk!');
                }
            })
        },
    }
}
</script>

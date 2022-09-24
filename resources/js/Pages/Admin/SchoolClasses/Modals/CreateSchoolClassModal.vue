<template>
    <FormModal :open="openModal" header="Nieuwe klas aanmaken" submit-text="Aanmaken"
               @close="close" @action="createClass" :disabled="disabled">
        <!-- School class information -->
        <h6>Klas informatie</h6>
        <form class="row g-3" @submit.prevent="createClass">
            <div class="col-md-6">
                <Input label="Klas naam" v-model="schoolClassForm.class_name" :error="schoolClassForm.errors.class_name">
                    <div class="form-text">Let op! Deze moet uniek zijn.</div>
                </Input>
            </div>
            <div class="col-md-6">
                <Input label="Afkorting mentor" v-model="schoolClassForm.mentor_abbreviation" :error="schoolClassForm.errors.mentor_abbreviation">
                    <div class="form-text">Dit is de afkorting van de docent.</div>
                </Input>
            </div>
        </form>

        <!-- All class students -->
        <div class="mt-3">
            <label class="mb-2">Selecteer leerlingen</label>

            <multiselect v-model="schoolClassForm.students" label="id" track-by="id" placeholder="Type om te zoeken..."
                         open-direction="bottom" :options="students" :multiple="true" :searchable="true" :loading="isLoading"
                         :internal-search="false" :clear-on-select="false" :close-on-select="false" :options-limit="50"
                         :limit="3" :limit-text="limitText" :max-height="400" :hide-selected="true"
                         @search-change="asyncFindStudents" :custom-label="customLabel" :show-labels="false"
                         :class="{'is-invalid': schoolClassForm.errors.students}">
                <template v-slot:noResult>
                    Geen studenten gevonden. Verander je zoekopdracht.
                </template>
                <template v-slot:noOptions>
                    Geen studenten.
                </template>
            </multiselect>
            <div class="invalid-feedback" v-if="schoolClassForm.errors.students">
                {{ schoolClassForm.errors.selectedStudents }}
            </div>
        </div>
    </FormModal>
</template>
<script>
import FormModal from "@/components/Modals/FormModal.vue";
import Input from "@/components/Inputs/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";

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
            disabled: false,
            students: [],
            isLoading: false,
            schoolClassForm: useForm({
                class_name: null,
                mentor_abbreviation: null,
                students: []
            })
        }
    },
    mounted() {
        this.asyncFindStudents();
    },
    methods: {
        asyncFindStudents (query) {
            this.isLoading = true;
            axios.get(route('admin.classes.get-students'), {
                params: {
                    query: query
                }
            })
                .then(response => {
                    this.students = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        limitText (count) {
            return `en ${count} andere studenten`
        },
        customLabel ({id, firstname, lastname}) {
            return `${id} – ${firstname} ${lastname}`
        },
        close() {
            this.$parent.openCreateModal = false;
        },
        createClass() {
            this.schoolClassForm.post(route('admin.classes.store'), {
                onBefore: () => {
                    this.disabled = true;
                },
                onSuccess: () => {
                    this.close();
                    this.schoolClassForm.reset();
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

<template>
    <FormModal :open="openModal" header="Nieuw vak aanmaken" submit-text="Aanmaken"
               @close="close" @action="createSubject" :disabled="subjectForm.processing">
        <form class="row g-3" @submit.prevent="createSubject()">
            <div class="col-md-12">
                <Input label="Naam" v-model="subjectForm.name" :error="subjectForm.errors.name" required/>
            </div>
            <div class="col-md-12">
                <Input label="Afkorting" v-model="subjectForm.abbreviation" :error="subjectForm.errors.abbreviation" required/>
            </div>
        </form>
    </FormModal>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/components/Modals/FormModal.vue";
import Input from "@/components/Inputs/Input.vue";

export default {
    name: 'CreateSubjectModal',
    props: {
        openModal: Boolean
    },
    components: {
        Input,
        FormModal,
        Dialog
    },
    data() {
        return {
            subjectForm: useForm({
                name: null,
                abbreviation: null
            })
        }
    },
    methods: {
        close() {
            this.$parent.openCreateModal = false;
        },
        createSubject() {
            this.subjectForm.post(route('admin.subjects.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.subjectForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', 'Vak is aangemaakt.')
                },
                    onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van dit vak. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

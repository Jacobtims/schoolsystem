<template>
    <ShowModal :open="openModal" header="Importeer studenten" @close="close">
        <form @submit.prevent="importStudents">
            <input class="form-control mb-3" :class="{'is-invalid': importForm.errors.file}" type="file"
                   accept=".xlsx, .xls, .csv" @change="importForm.file = $event.target.files[0]"/>
            <div class="invalid-feedback" v-if="importForm.errors.file">{{ importForm.errors.file }}</div>

            <Checkbox v-model="importForm.generatePassword" label="Automatisch wachtwoord genereren"
                      :error="importForm.errors.generatePassword"/>

            <Input label="Wachtwoord" v-model="importForm.password" :error="importForm.errors.password" type="password"
                   v-if="!importForm.generatePassword" class="mb-3"/>

            <Checkbox v-model="importForm.sendEmail" label="Verstuur e-mail naar nieuwe studenten"
                      :error="importForm.errors.sendEmail"/>

            <p class="text-danger mt-4">Let op! Dit kan enige tijd duren.</p>

            <button class="btn btn-success" :disabled="importForm.processing">
                Import
                <div class="spinner-border spinner-border-sm text-light" v-if="importForm.processing">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>
        </form>
    </ShowModal>
</template>
<script>
import ShowModal from "@/Components/Modals/ShowModal.vue";
import Input from "@/Components/Inputs/Input.vue";
import Checkbox from "@/Components/Inputs/Checkbox.vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    components: {ShowModal, Input, Checkbox},
    props: {
        openModal: Boolean,
    },
    data() {
        return {
            importForm: useForm({
                file: null,
                generatePassword: true,
                password: null,
                sendEmail: true,
            }),
        }
    },
    methods: {
        close() {
            this.$parent.openImportModal = false;
        },
        importStudents() {
            this.importForm.post(route('admin.students.import'), {
                preserveScroll: true,
                onSuccess: (page) => {
                    this.close();
                    this.importForm.reset();
                    this.toast('success', 'Succesvol geïmporteerd!', `Er zijn ${page.props.data.students_count} studenten succesvol geïmporteerd!`);
                    // Show errors count
                    if (page.props.data.errors) {
                        this.toast('error', `Er is bij ${page.props.data.errors} studenten iets fout gegaan tijdens het importeren.`);
                    }
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan bij het importeren van de studenten. Probeer het opnieuw.');
                }
            });
        }
    }
}
</script>

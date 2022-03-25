<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Nieuw vak aanmaken" :draggable="false" :modal="true" @hide="close">
        <div>
            <form class="row g-3" @submit.prevent="createSubject()">
                <div class="col-md-12">
                    <label for="inputName" class="form-label">Naam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': subjectForm.errors.name}"
                           id="inputName" v-model="subjectForm.name" aria-describedby="feedbackName" required>
                    <div id="feedbackName" class="invalid-feedback" v-if="subjectForm.errors.name">
                        {{ subjectForm.errors.name }}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputAbbreviation" class="form-label">Afkorting</label>
                    <input type="text" class="form-control" :class="{'is-invalid': subjectForm.errors.abbreviation}"
                           id="inputAbbreviation" v-model="subjectForm.abbreviation" aria-describedby="feedbackAbbreviation" required>
                    <div id="feedbackAbbreviation" class="invalid-feedback" v-if="subjectForm.errors.abbreviation">
                        {{ subjectForm.errors.abbreviation }}
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" @click="createSubject()" autofocus :disabled="subjectForm.processing"><i class="fa-solid fa-check"></i> Aanmaken</button>
            <button class="btn btn-secondary" @click="close" :disabled="subjectForm.processing"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: 'CreateSubjectModal',
    props: {
        openModal: Boolean
    },
    components: {
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

<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Nieuw lokaal aanmaken" :draggable="false" :modal="true" @hide="close">
        <div>
            <form class="row g-3" @submit.prevent="createClassroom()">
                <div class="col-md-12">
                    <label for="inputName" class="form-label">Naam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': classroomForm.errors.name}"
                           id="inputName" v-model="classroomForm.name" aria-describedby="feedbackName" required>
                    <div id="feedbackName" class="invalid-feedback" v-if="classroomForm.errors.name">
                        {{ classroomForm.errors.name }}
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" @click="createClassroom()" autofocus :disabled="classroomForm.processing"><i class="fa-solid fa-check"></i> Aanmaken</button>
            <button class="btn btn-secondary" @click="close" :disabled="classroomForm.processing"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: 'CreateClassroomModal',
    props: {
        openModal: Boolean
    },
    components: {
        Dialog
    },
    data() {
        return {
            classroomForm: useForm({
                name: null
            })
        }
    },
    methods: {
        close() {
            this.$parent.openCreateModal = false;
        },
        createClassroom() {
            this.classroomForm.post(route('admin.classrooms.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.classroomForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', 'Lokaal is aangemaakt.')
                },
                    onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van dit lokaal. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

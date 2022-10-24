<template>
    <FormModal :open="openModal" header="Nieuw lokaal aanmaken" submit-text="Aanmaken"
               @close="close" @action="createClassroom" :disabled="classroomForm.processing">
        <form class="row g-3" @submit.prevent="createClassroom()">
            <div class="col-md-12">
                <Input label="Naam" v-model="classroomForm.name" :error="classroomForm.errors.name" required/>
            </div>
        </form>
    </FormModal>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/Components/Modals/FormModal.vue";
import Input from "@/Components/Inputs/InputWithLabel.vue";

export default {
    name: 'CreateClassroomModal',
    props: {
        openModal: Boolean
    },
    components: {
        Input,
        FormModal
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

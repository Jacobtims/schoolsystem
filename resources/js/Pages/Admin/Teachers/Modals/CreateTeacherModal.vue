<template>
    <FormModal :open="openModal" header="Nieuwe docent aanmaken" submit-text="Aanmaken"
               @close="close" @action="createTeacher" :disabled="teacherForm.processing">
        <form class="row g-3" @submit.prevent="createTeacher()">
            <div class="col-md-5">
                <Input label="Afkorting" v-model="teacherForm.abbreviation" :error="teacherForm.errors.abbreviation" min="2" required/>
            </div>
            <div class="col-md-7">
                <Input label="Studenten naam" v-model="teacherForm.student_name" :error="teacherForm.errors.student_name" min="2" required/>
            </div>
            <div class="col-md-5">
                <Input label="Voornaam" v-model="teacherForm.firstname" :error="teacherForm.errors.firstname" min="2" required/>
            </div>
            <div class="col-md-7">
                <Input label="Achternaam" v-model="teacherForm.lastname" :error="teacherForm.errors.lastname" min="2" required/>
            </div>
            <div class="col-md-12">
                <Input label="E-mailadres" v-model="teacherForm.email" :error="teacherForm.errors.email" type="email" required/>
            </div>
            <div class="col-md-6">
                <Input label="Straatnaam + huisnummer" v-model="teacherForm.street" :error="teacherForm.errors.street" required/>
            </div>
            <div class="col-md-6">
                <Input label="Woonplaats" v-model="teacherForm.city" :error="teacherForm.errors.city" required/>
            </div>
            <div class="col-md-5">
                <Input label="Land" v-model="teacherForm.country" :error="teacherForm.errors.country" required/>
            </div>
            <div class="col-md-5">
                <Input label="Provincie" v-model="teacherForm.state" :error="teacherForm.errors.state" required/>
            </div>
            <div class="col-md-2">
                <Input label="Postcode" v-model="teacherForm.zipcode" :error="teacherForm.errors.zipcode" required/>
            </div>
            <div class="col-md-6">
                <Input label="Telefoonnummer" v-model="teacherForm.phone_number" :error="teacherForm.errors.phone_number" type="phone" required/>
            </div>
            <div class="col-md-6">
                <Input label="Geboortedatum" v-model="teacherForm.date_of_birth" :error="teacherForm.errors.date_of_birth" type="date" required/>
            </div>
            <div class="col-md-12">
                <Checkbox label="Automatisch wachtwoord genereren" v-model="teacherForm.generatePassword" :error="teacherForm.errors.generatePassword"/>

                <Input label="Wachtwoord" v-model="teacherForm.password" :error="teacherForm.errors.password" type="password" v-if="!teacherForm.generatePassword" class="mb-3"/>

                <Checkbox v-model="teacherForm.sendEmail" label="Verstuur e-mail naar nieuwe student" :error="teacherForm.errors.sendEmail"/>
            </div>
        </form>
    </FormModal>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/components/Modals/FormModal.vue";
import Input from "@/components/Inputs/Input.vue";
import Checkbox from "@/components/Inputs/Checkbox.vue";

export default {
    name: 'CreateTeacherModal',
    props: {
        openModal: Boolean
    },
    components: {
        Input,
        FormModal,
        Checkbox,
        Dialog
    },
    data() {
        return {
            teacherForm: useForm({
                firstname: null,
                lastname: null,
                email: null,
                street: null,
                city: null,
                country: 'Nederland',
                state: null,
                zipcode: null,
                phone_number: null,
                date_of_birth: null,
                password: null,
                generatePassword: true,
                sendEmail: true,
                abbreviation: null,
                student_name: null
            })
        }
    },
    methods: {
        close() {
            this.$parent.openCreateModal = false;
        },
        createTeacher() {
            this.teacherForm.post(route('admin.teachers.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.teacherForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', 'Docent is aangemaakt.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van deze docent. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

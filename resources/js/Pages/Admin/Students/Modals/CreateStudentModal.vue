<template>
    <FormModal :open="openModal" header="Nieuwe student aanmaken" submit-text="Aanmaken"
               @close="close" @action="createStudent" :disabled="studentForm.processing">
        <form class="row g-3" @submit.prevent="createStudent()">
            <div class="col-md-5">
                <Input label="Voornaam" v-model="studentForm.firstname" :error="studentForm.errors.firstname" min="2" required/>
            </div>
            <div class="col-md-7">
                <Input label="Achternaam" v-model="studentForm.lastname" :error="studentForm.errors.lastname" min="2" required/>
            </div>
            <div class="col-md-12">
                <Input label="E-mailadres" v-model="studentForm.email" :error="studentForm.errors.email" type="email" required/>
            </div>
            <div class="col-md-6">
                <Input label="Straatnaam + huisnummer" v-model="studentForm.street" :error="studentForm.errors.street" required/>
            </div>
            <div class="col-md-6">
                <Input label="Woonplaats" v-model="studentForm.city" :error="studentForm.errors.city" required/>
            </div>
            <div class="col-md-5">
                <Input label="Land" v-model="studentForm.country" :error="studentForm.errors.country" required/>
            </div>
            <div class="col-md-5">
                <Input label="Provincie" v-model="studentForm.state" :error="studentForm.errors.state" required/>
            </div>
            <div class="col-md-2">
                <Input label="Postcode" v-model="studentForm.zipcode" :error="studentForm.errors.zipcode" required/>
            </div>
            <div class="col-md-6">
                <Input label="Telefoonnummer" v-model="studentForm.phone_number" :error="studentForm.errors.phone_number" required/>
            </div>
            <div class="col-md-6">
                <Input label="Geboortedatum" v-model="studentForm.date_of_birth" :error="studentForm.errors.date_of_birth" type="date" required/>
            </div>
            <div class="col-md-12">
                <Checkbox v-model="studentForm.generatePassword" label="Automatisch wachtwoord genereren" :error="studentForm.errors.generatePassword"/>

                <Input label="Wachtwoord" v-model="studentForm.password" :error="studentForm.errors.password" type="password" v-if="!studentForm.generatePassword" class="mb-3"/>

                <Checkbox v-model="studentForm.sendEmail" label="Verstuur e-mail naar nieuwe student" :error="studentForm.errors.sendEmail"/>
            </div>
        </form>
    </FormModal>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import Input from "@/components/Inputs/Input.vue";
import Checkbox from "@/components/Inputs/Checkbox.vue";
import FormModal from "@/Components/Modals/FormModal.vue";

export default {
    name: 'CreateStudentModal',
    props: {
        openModal: Boolean
    },
    components: {
        FormModal,
        Input,
        Checkbox
    },
    data() {
        return {
            studentForm: useForm({
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
                sendEmail: true
            })
        }
    },
    methods: {
        close() {
            this.$parent.openCreateModal = false;
        },
        createStudent() {
            this.studentForm.post(route('admin.students.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.studentForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangemaakt!', 'Student is aangemaakt.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van deze student. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

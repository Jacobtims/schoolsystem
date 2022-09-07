<template>
    <FormModal :open="openModal" header="Student bewerken" submit-text="Aanpassen"
               @close="close" @action="editStudent" :disabled="studentForm.processing" v-if="user">
        <form class="row g-3" @submit.prevent="editStudent()">
            <div class="col-md-12">
                <Input label="Leerlingnummer" v-model="studentForm.student_id" :error="studentForm.errors.student_id" type="number" readonly/>
            </div>
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
                <Input label="Telefoonnummer" v-model="studentForm.phone_number" :error="studentForm.errors.phone_number" type="tel" required/>
            </div>
            <div class="col-md-6">
                <Input label="Geboortedatum" v-model="studentForm.date_of_birth" :error="studentForm.errors.date_of_birth" type="date" required/>
            </div>
            <div class="col-md-6">
                <label for="inputBirthday" class="form-label">Wachtwoord reset</label><br/>
                <small>Verstuurd een link naar e-mailadres <strong>{{ studentForm.email }}</strong> om het wachtwoord mee te kunnen resetten.</small>
            </div>
            <div class="col-md-6">
                <button class="btn btn-secondary" type="button">Verstuur reset link</button>
            </div>
        </form>
    </FormModal>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/Components/Modals/FormModal.vue";
import Input from "@/Components/Inputs/Input.vue";

export default {
    name: 'EditStudentModal',
    props: {
        openModal: Boolean,
        user: Object
    },
    components: {
        Input,
        FormModal
    },
    data() {
        return {
            studentForm: useForm({
                id: null,
                firstname: null,
                lastname: null,
                email: null,
                street: null,
                city: null,
                country: null,
                state: null,
                zipcode: null,
                phone_number: null,
                date_of_birth: null,
                student_id: null
            })
        }
    },
    watch: {
        user: function (newUser) {
            if (newUser != null) {
                this.studentForm.id = newUser.id ?? null,
                this.studentForm.firstname = newUser.firstname ?? null,
                this.studentForm.lastname = newUser.lastname ?? null,
                this.studentForm.email = newUser.email ?? null,
                this.studentForm.street = newUser.street ?? null,
                this.studentForm.city = newUser.city ?? null,
                this.studentForm.country = newUser.country ?? null,
                this.studentForm.state = newUser.state ?? null,
                this.studentForm.zipcode = newUser.zipcode ?? null,
                this.studentForm.phone_number = newUser.phone_number ?? null,
                this.studentForm.date_of_birth = newUser.date_of_birth ?? null,
                this.studentForm.student_id = newUser.student_id ?? null
            }
        }
    },
    methods: {
        close() {
            this.$parent.openEditModal = false;
            this.$parent.activeStudent = null;
            this.studentForm.reset();
        },
        editStudent() {
            this.studentForm.patch(route('admin.students.update', this.studentForm.id), {
                preserveScroll: true,
                onSuccess: () => {
                    this.studentForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangepast!', 'Student is aangepast.')
                },
                    onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanpassen van deze student. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

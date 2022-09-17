<template>
    <FormModal :open="openModal" header="Docent bewerken" submit-text="Aanpassen"
               @close="close" @action="editTeacher" :disabled="teacherForm.processing" v-if="teacher">
        <form class="row g-3" @submit.prevent="editTeacher()">
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
            <div class="col-md-8">
                <Input label="E-mailadres" v-model="teacherForm.email" :error="teacherForm.errors.email" type="email" required/>
            </div>
            <div class="col-md-4">
                <Select label="Geslacht" v-model="teacherForm.sex" :error="teacherForm.errors.sex" required>
                    <option selected disabled></option>
                    <option value="m">Man</option>
                    <option value="v">Vrouw</option>
                    <option value="o">Overig</option>
                </Select>
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
            <div class="col-md-6">
                <label for="inputBirthday" class="form-label">Wachtwoord reset</label><br/>
                <small>Verstuurd een link naar e-mailadres <strong>{{ teacherForm.email }}</strong> om het wachtwoord mee te kunnen resetten.</small>
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
import Select from "@/Components/Inputs/Select.vue";

export default {
    name: 'EditTeacherModal',
    props: {
        openModal: Boolean,
        teacher: Object
    },
    components: {
        FormModal,
        Input,
        Select
    },
    data() {
        return {
            teacherForm: useForm({
                id: null,
                firstname: null,
                lastname: null,
                sex: null,
                email: null,
                street: null,
                city: null,
                country: null,
                state: null,
                zipcode: null,
                phone_number: null,
                date_of_birth: null,
                abbreviation: null,
                student_name: null
            })
        }
    },
    watch: {
        teacher: function (newUser) {
            if (newUser != null) {
                this.teacherForm.id = newUser.id ?? null,
                this.teacherForm.sex = newUser.sex_raw ?? null,
                this.teacherForm.firstname = newUser.firstname ?? null,
                this.teacherForm.lastname = newUser.lastname ?? null,
                this.teacherForm.email = newUser.email ?? null,
                this.teacherForm.street = newUser.street ?? null,
                this.teacherForm.city = newUser.city ?? null,
                this.teacherForm.country = newUser.country ?? null,
                this.teacherForm.state = newUser.state ?? null,
                this.teacherForm.zipcode = newUser.zipcode ?? null,
                this.teacherForm.phone_number = newUser.phone_number ?? null,
                this.teacherForm.date_of_birth = newUser.date_of_birth ?? null,
                this.teacherForm.abbreviation = newUser.abbreviation ?? null,
                this.teacherForm.student_name = newUser.student_name ?? null
            }
        }
    },
    methods: {
        close() {
            this.$parent.openEditModal = false;
            this.$parent.activeTeacher = null;
            this.teacherForm.reset();
        },
        editTeacher() {
            this.teacherForm.patch(route('admin.teachers.update', this.teacherForm.id), {
                preserveScroll: true,
                onSuccess: () => {
                    this.teacherForm.reset();
                    this.close();
                    this.toast('success', 'Succesvol aangepast!', 'Docent is aangepast.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanpassen van deze docent. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>

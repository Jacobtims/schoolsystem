<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Student gegevens" :draggable="false" :modal="true"
            @hide="close" v-if="user">
        <div>
            <form class="row g-3" @submit.prevent="editStudent()">
                <div class="col-md-12">
                    <label for="inputStudentId" class="form-label">Leerlingnummer</label>
                    <input type="number" class="form-control" id="inputStudentId" readonly v-model="studentForm.student_id">
                </div>
                <div class="col-md-5">
                    <label for="inputFirstname" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" id="inputFirstname" v-model="studentForm.firstname">
                </div>
                <div class="col-md-7">
                    <label for="inputLastname" class="form-label">Achternaam</label>
                    <input type="text" class="form-control" id="inputLastname" v-model="studentForm.lastname">
                </div>
                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mailadres</label>
                    <input type="text" class="form-control" id="inputEmail" v-model="studentForm.email">
                </div>
                <div class="col-md-6">
                    <label for="inputAddress" class="form-label">Straatnaam + huisnummer</label>
                    <input type="text" class="form-control" id="inputAddress" v-model="studentForm.street">
                </div>
                <div class="col-md-6">
                    <label for="inputCity" class="form-label">Woonplaats</label>
                    <input type="text" class="form-control" id="inputCity" v-model="studentForm.city">
                </div>
                <div class="col-md-5">
                    <label for="inputCountry" class="form-label">Land</label>
                    <input type="text" class="form-control" id="inputCountry" v-model="studentForm.country">
                </div>
                <div class="col-md-5">
                    <label for="inputState" class="form-label">Provincie</label>
                    <input type="text" class="form-control" id="inputState" v-model="studentForm.state">
                </div>
                <div class="col-md-2">
                    <label for="inputZip" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="inputZip" v-model="studentForm.zipcode">
                </div>
                <div class="col-md-6">
                    <label for="inputPhone" class="form-label">Telefoonnummer</label>
                    <input type="tel" class="form-control" id="inputPhone" v-model="studentForm.phone_number">
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Geboortedatum</label>
                    <input type="date" class="form-control" id="inputBirthday" v-model="studentForm.date_of_birth">
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Wachtwoord reset</label><br/>
                    <small>Verstuurd een link naar e-mailadres <strong>{{ studentForm.email }}</strong> om het wachtwoord mee te kunnen resetten.</small>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-secondary" type="button">Verstuur reset link</button>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" autofocus @click="editStudent()"><i class="fa-solid fa-check"></i> Aanpassen</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";
export default {
    name: 'EditStudentModal',
    props: {
        openModal: Boolean,
        user: Object
    },
    components: {
        Dialog
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
                },
            })
        }
    }
}
</script>

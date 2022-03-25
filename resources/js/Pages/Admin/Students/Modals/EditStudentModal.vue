<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Student bewerken" :draggable="false" :modal="true"
            @hide="close" v-if="user">
        <div>
            <form class="row g-3" @submit.prevent="editStudent()">
                <div class="col-md-12">
                    <label for="inputStudentId" class="form-label">Leerlingnummer</label>
                    <input type="number" class="form-control" id="inputStudentId" readonly v-model="studentForm.student_id">
                </div>
                <div class="col-md-5">
                    <label for="inputFirstname" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.firstname}" id="inputFirstname" v-model="studentForm.firstname" aria-describedby="feedbackFirstname" required min="2">
                    <div id="feedbackFirstname" class="invalid-feedback" v-if="studentForm.errors.firstname">
                        {{ studentForm.errors.firstname }}
                    </div>
                </div>
                <div class="col-md-7">
                    <label for="inputLastname" class="form-label">Achternaam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.lastname}" id="inputLastname" v-model="studentForm.lastname" aria-describedby="feedbackLastname" required min="2">
                    <div id="feedbackLastname" class="invalid-feedback" v-if="studentForm.errors.lastname">
                        {{ studentForm.errors.lastname }}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mailadres</label>
                    <input type="email" class="form-control" :class="{'is-invalid': studentForm.errors.email}" id="inputEmail" v-model="studentForm.email" aria-describedby="feedbackEmail" required>
                    <div id="feedbackEmail" class="invalid-feedback" v-if="studentForm.errors.email">
                        {{ studentForm.errors.email }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputAddress" class="form-label">Straatnaam + huisnummer</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.street}" id="inputAddress" v-model="studentForm.street" aria-describedby="feedbackStreet" required>
                    <div id="feedbackStreet" class="invalid-feedback" v-if="studentForm.errors.street">
                        {{ studentForm.errors.street }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputCity" class="form-label">Woonplaats</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.city}" id="inputCity" v-model="studentForm.city" aria-describedby="feedbackCity" required>
                    <div id="feedbackCity" class="invalid-feedback" v-if="studentForm.errors.city">
                        {{ studentForm.errors.city }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputCountry" class="form-label">Land</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.country}" id="inputCountry" v-model="studentForm.country" aria-describedby="feedbackCountry" required>
                    <div id="feedbackCountry" class="invalid-feedback" v-if="studentForm.errors.country">
                        {{ studentForm.errors.country }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputState" class="form-label">Provincie</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.state}" id="inputState" v-model="studentForm.state" aria-describedby="feedbackState" required>
                    <div id="feedbackState" class="invalid-feedback" v-if="studentForm.errors.state">
                        {{ studentForm.errors.state }}
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="inputZip" class="form-label">Zip</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.zipcode}" id="inputZip" v-model="studentForm.zipcode" aria-describedby="feedbackZipcode" required>
                    <div id="feedbackZipcode" class="invalid-feedback" v-if="studentForm.errors.zipcode">
                        {{ studentForm.errors.zipcode }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputPhone" class="form-label">Telefoonnummer</label>
                    <input type="tel" class="form-control" :class="{'is-invalid': studentForm.errors.phone_number}" id="inputPhone" v-model="studentForm.phone_number" aria-describedby="feedbackPhoneNumber" required>
                    <div id="feedbackPhoneNumber" class="invalid-feedback" v-if="studentForm.errors.phone_number">
                        {{ studentForm.errors.phone_number }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Geboortedatum</label>
                    <input type="date" class="form-control" :class="{'is-invalid': studentForm.errors.date_of_birth}" id="inputBirthday" v-model="studentForm.date_of_birth" aria-describedby="feedbackDateOfBirth" required>
                    <div id="feedbackDateOfBirth" class="invalid-feedback" v-if="studentForm.errors.date_of_birth">
                        {{ studentForm.errors.date_of_birth }}
                    </div>
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

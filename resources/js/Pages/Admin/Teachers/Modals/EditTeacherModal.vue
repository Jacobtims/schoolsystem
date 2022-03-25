<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Docent bewerken" :draggable="false" :modal="true"
            @hide="close" v-if="teacher">
        <div>
            <form class="row g-3" @submit.prevent="editTeacher()">
                <div class="col-md-5">
                    <label for="inputAbbreviation" class="form-label">Afkorting</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.abbreviation}" id="inputAbbreviation" v-model="teacherForm.abbreviation" aria-describedby="feedbackAbbreviation" required min="2">
                    <div id="feedbackAbbreviation" class="invalid-feedback" v-if="teacherForm.errors.abbreviation">
                        {{ teacherForm.errors.abbreviation }}
                    </div>
                </div>
                <div class="col-md-7">
                    <label for="inputStudentName" class="form-label">Studenten naam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.student_name}" id="inputStudentName" v-model="teacherForm.student_name" aria-describedby="feedbackStudentName" required min="2">
                    <div id="feedbackStudentName" class="invalid-feedback" v-if="teacherForm.errors.student_name">
                        {{ teacherForm.errors.student_name }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputFirstname" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.firstname}" id="inputFirstname" v-model="teacherForm.firstname" aria-describedby="feedbackFirstname" required min="2">
                    <div id="feedbackFirstname" class="invalid-feedback" v-if="teacherForm.errors.firstname">
                        {{ teacherForm.errors.firstname }}
                    </div>
                </div>
                <div class="col-md-7">
                    <label for="inputLastname" class="form-label">Achternaam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.lastname}" id="inputLastname" v-model="teacherForm.lastname" aria-describedby="feedbackLastname" required min="2">
                    <div id="feedbackLastname" class="invalid-feedback" v-if="teacherForm.errors.lastname">
                        {{ teacherForm.errors.lastname }}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mailadres</label>
                    <input type="email" class="form-control" :class="{'is-invalid': teacherForm.errors.email}" id="inputEmail" v-model="teacherForm.email" aria-describedby="feedbackEmail" required>
                    <div id="feedbackEmail" class="invalid-feedback" v-if="teacherForm.errors.email">
                        {{ teacherForm.errors.email }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputAddress" class="form-label">Straatnaam + huisnummer</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.street}" id="inputAddress" v-model="teacherForm.street" aria-describedby="feedbackStreet" required>
                    <div id="feedbackStreet" class="invalid-feedback" v-if="teacherForm.errors.street">
                        {{ teacherForm.errors.street }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputCity" class="form-label">Woonplaats</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.city}" id="inputCity" v-model="teacherForm.city" aria-describedby="feedbackCity" required>
                    <div id="feedbackCity" class="invalid-feedback" v-if="teacherForm.errors.city">
                        {{ teacherForm.errors.city }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputCountry" class="form-label">Land</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.country}" id="inputCountry" v-model="teacherForm.country" aria-describedby="feedbackCountry" required>
                    <div id="feedbackCountry" class="invalid-feedback" v-if="teacherForm.errors.country">
                        {{ teacherForm.errors.country }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputState" class="form-label">Provincie</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.state}" id="inputState" v-model="teacherForm.state" aria-describedby="feedbackState" required>
                    <div id="feedbackState" class="invalid-feedback" v-if="teacherForm.errors.state">
                        {{ teacherForm.errors.state }}
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="inputZip" class="form-label">Zip</label>
                    <input type="text" class="form-control" :class="{'is-invalid': teacherForm.errors.zipcode}" id="inputZip" v-model="teacherForm.zipcode" aria-describedby="feedbackZipcode" required>
                    <div id="feedbackZipcode" class="invalid-feedback" v-if="teacherForm.errors.zipcode">
                        {{ teacherForm.errors.zipcode }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputPhone" class="form-label">Telefoonnummer</label>
                    <input type="tel" class="form-control" :class="{'is-invalid': teacherForm.errors.phone_number}" id="inputPhone" v-model="teacherForm.phone_number" aria-describedby="feedbackPhoneNumber" required>
                    <div id="feedbackPhoneNumber" class="invalid-feedback" v-if="teacherForm.errors.phone_number">
                        {{ teacherForm.errors.phone_number }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Geboortedatum</label>
                    <input type="date" class="form-control" :class="{'is-invalid': teacherForm.errors.date_of_birth}" id="inputBirthday" v-model="teacherForm.date_of_birth" aria-describedby="feedbackDateOfBirth" required>
                    <div id="feedbackDateOfBirth" class="invalid-feedback" v-if="teacherForm.errors.date_of_birth">
                        {{ teacherForm.errors.date_of_birth }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Wachtwoord reset</label><br/>
                    <small>Verstuurd een link naar e-mailadres <strong>{{ teacherForm.email }}</strong> om het wachtwoord mee te kunnen resetten.</small>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-secondary" type="button">Verstuur reset link</button>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" autofocus @click="editTeacher()"><i class="fa-solid fa-check"></i> Aanpassen</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";
export default {
    name: 'EditTeacherModal',
    props: {
        openModal: Boolean,
        teacher: Object
    },
    components: {
        Dialog
    },
    data() {
        return {
            teacherForm: useForm({
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
                abbreviation: null,
                student_name: null
            })
        }
    },
    watch: {
        teacher: function (newUser) {
            if (newUser != null) {
                this.teacherForm.id = newUser.id ?? null,
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

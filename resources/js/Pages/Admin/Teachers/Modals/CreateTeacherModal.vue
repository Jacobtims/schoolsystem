<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Nieuwe docent aanmaken" :draggable="false" :modal="true" @hide="close">
        <div>
            <form class="row g-3" @submit.prevent="createTeacher()">
                <div class="col-md-5">
                    <label for="inputAbbreviation" class="form-label">Afkorting</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.abbreviation}"
                           id="inputAbbreviation" v-model="studentForm.abbreviation" aria-describedby="feedbackAbbreviation"
                           required min="2">
                    <div id="feedbackAbbreviation" class="invalid-feedback" v-if="studentForm.errors.abbreviation">
                        {{ studentForm.errors.abbreviation }}
                    </div>
                </div>
                <div class="col-md-7">
                    <label for="inputStudentName" class="form-label">Studenten naam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.student_name}"
                           id="inputStudentName" v-model="studentForm.student_name" aria-describedby="feedbackStudentName"
                           required min="2">
                    <div id="feedbackStudentName" class="invalid-feedback" v-if="studentForm.errors.student_name">
                        {{ studentForm.errors.student_name }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputFirstname" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.firstname}"
                           id="inputFirstname" v-model="studentForm.firstname" aria-describedby="feedbackFirstname"
                           required min="2">
                    <div id="feedbackFirstname" class="invalid-feedback" v-if="studentForm.errors.firstname">
                        {{ studentForm.errors.firstname }}
                    </div>
                </div>
                <div class="col-md-7">
                    <label for="inputLastname" class="form-label">Achternaam</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.lastname}"
                           id="inputLastname" v-model="studentForm.lastname" aria-describedby="feedbackLastname"
                           required min="2">
                    <div id="feedbackLastname" class="invalid-feedback" v-if="studentForm.errors.lastname">
                        {{ studentForm.errors.lastname }}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mailadres</label>
                    <input type="email" class="form-control" :class="{'is-invalid': studentForm.errors.email}"
                           id="inputEmail" v-model="studentForm.email" aria-describedby="feedbackEmail" required>
                    <div id="feedbackEmail" class="invalid-feedback" v-if="studentForm.errors.email">
                        {{ studentForm.errors.email }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputAddress" class="form-label">Straatnaam + huisnummer</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.street}"
                           id="inputAddress" v-model="studentForm.street" aria-describedby="feedbackStreet" required>
                    <div id="feedbackStreet" class="invalid-feedback" v-if="studentForm.errors.street">
                        {{ studentForm.errors.street }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputCity" class="form-label">Woonplaats</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.city}"
                           id="inputCity" v-model="studentForm.city" aria-describedby="feedbackCity" required>
                    <div id="feedbackCity" class="invalid-feedback" v-if="studentForm.errors.city">
                        {{ studentForm.errors.city }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputCountry" class="form-label">Land</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.country}"
                           id="inputCountry" v-model="studentForm.country" aria-describedby="feedbackCountry" required>
                    <div id="feedbackCountry" class="invalid-feedback" v-if="studentForm.errors.country">
                        {{ studentForm.errors.country }}
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="inputState" class="form-label">Provincie</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.state}"
                           id="inputState" v-model="studentForm.state" aria-describedby="feedbackState" required>
                    <div id="feedbackState" class="invalid-feedback" v-if="studentForm.errors.state">
                        {{ studentForm.errors.state }}
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="inputZip" class="form-label">Postcode</label>
                    <input type="text" class="form-control" :class="{'is-invalid': studentForm.errors.zipcode}"
                           id="inputZip" v-model="studentForm.zipcode" aria-describedby="feedbackZipcode" required>
                    <div id="feedbackZipcode" class="invalid-feedback" v-if="studentForm.errors.zipcode">
                        {{ studentForm.errors.zipcode }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputPhone" class="form-label">Telefoonnummer</label>
                    <input type="tel" class="form-control" :class="{'is-invalid': studentForm.errors.phone_number}"
                           id="inputPhone" v-model="studentForm.phone_number" aria-describedby="feedbackPhoneNumber"
                           required>
                    <div id="feedbackPhoneNumber" class="invalid-feedback" v-if="studentForm.errors.phone_number">
                        {{ studentForm.errors.phone_number }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Geboortedatum</label>
                    <input type="date" class="form-control" :class="{'is-invalid': studentForm.errors.date_of_birth}"
                           id="inputBirthday" v-model="studentForm.date_of_birth" aria-describedby="feedbackDateOfBirth"
                           required>
                    <div id="feedbackDateOfBirth" class="invalid-feedback" v-if="studentForm.errors.date_of_birth">
                        {{ studentForm.errors.date_of_birth }}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkGeneratePassword"
                               v-model="studentForm.generatePassword">
                        <label class="form-check-label" for="checkGeneratePassword">Automatisch wachtwoord
                            genereren</label>
                    </div>
                    <label for="inputPassword" class="form-label"
                           v-if="!studentForm.generatePassword">Wachtwoord</label>
                    <input type="password" class="form-control mb-3"
                           :class="{'is-invalid': studentForm.errors.generatePassword}" id="inputPassword"
                           v-model="studentForm.password" v-if="!studentForm.generatePassword"
                           aria-describedby="feedbackGeneratePassword">
                    <div id="feedbackGeneratePassword" class="invalid-feedback"
                         v-if="!studentForm.generatePassword && studentForm.errors.generatePassword">
                        {{ studentForm.errors.generatePassword }}
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkSendEmail" v-model="studentForm.sendEmail">
                        <label class="form-check-label" for="checkSendEmail">Verstuur e-mail naar nieuwe student</label>
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" @click="createTeacher()" autofocus :disabled="studentForm.processing"><i class="fa-solid fa-check"></i> Aanmaken</button>
            <button class="btn btn-secondary" @click="close" :disabled="studentForm.processing"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: 'CreateTeacherModal',
    props: {
        openModal: Boolean
    },
    components: {
        Dialog
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
            this.studentForm.post(route('admin.teachers.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.studentForm.reset();
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

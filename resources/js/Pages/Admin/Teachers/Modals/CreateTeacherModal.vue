<template>
    <FormModal :open="openModal" header="Nieuwe docent aanmaken" submit-text="Aanmaken"
               @close="close" @action="createTeacher" :disabled="teacherForm.processing">
        <form class="row g-3" @submit.prevent="createTeacher()">
            <div class="col-md-12 d-flex gap-3 align-items-end mb-1">
                <!-- Profile image -->
                <div class="profile-img-container">
                    <input type="file" @change="uploadProfileImage" name="profile_image" ref="profile_image" class="d-none" accept=".jpg, .jpeg, .png">
                    <img :src="profile_photo" class="rounded-circle"/>
                    <section class="btn-wrapper">
                        <button class="btn" type="button" @click="$refs.profile_image.click()"><i class="fa-solid fa-pen-to-square"></i></button>
                    </section>
                </div>
                <!-- Leerlingnummer -->
                <div class="w-100">
                    <Input label="Afkorting" v-model="teacherForm.abbreviation" :error="teacherForm.errors.abbreviation" min="2" required/>
                </div>
            </div>
            <div class="col-md-12 invalid-feedback d-block" v-if="teacherForm.errors.profile_photo">
                {{ teacherForm.errors.profile_photo }}
            </div>
            <div class="col-md-12">
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
            <div class="col-md-12">
                <Checkbox label="Automatisch wachtwoord genereren" v-model="teacherForm.generatePassword" :error="teacherForm.errors.generatePassword"/>

                <Input label="Wachtwoord" v-model="teacherForm.password" :error="teacherForm.errors.password" type="password" v-if="!teacherForm.generatePassword" class="mb-3"/>

                <Checkbox v-model="teacherForm.sendEmail" label="Verstuur e-mail naar nieuwe docent" :error="teacherForm.errors.sendEmail"/>
            </div>
        </form>
    </FormModal>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/components/Modals/FormModal.vue";
import Input from "@/components/Inputs/Input.vue";
import Checkbox from "@/components/Inputs/Checkbox.vue";
import Select from "@/Components/Inputs/Select.vue";

export default {
    name: 'CreateTeacherModal',
    props: {
        openModal: Boolean
    },
    components: {
        Input,
        FormModal,
        Checkbox,
        Select
    },
    data() {
        return {
            teacherForm: useForm({
                firstname: null,
                lastname: null,
                sex: null,
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
                student_name: null,
                profile_photo: null
            }),
            profile_photo: null
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
        },
        uploadProfileImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();

            if(file['size'] > 2111775) {
                this.toast('error', 'Bestand te groot', 'Profielfoto mag niet groter zijn dan 2MB!');
                return;
            }
            if (file['type'] !== 'image/jpg' && file['type'] !== 'image/jpeg' && file['type'] !== 'image/png') {
                this.toast('error', 'Onjuiste bestandstype', 'Profielfoto heeft een onjuist bestandstype!');
                return;
            }

            // Load file
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.profile_photo = e.target.result;
            }
            this.teacherForm.profile_photo = file;
        }
    }
}
</script>

<template>
    <FormModal :open="openModal" header="Nieuwe student aanmaken" submit-text="Aanmaken"
               @close="close" @action="createStudent" :disabled="studentForm.processing">
        <form class="row g-3" @submit.prevent="createStudent()">
            <div class="col-md-12 d-flex gap-3 align-items-end mb-1">
                <!-- Profile image -->
                <div class="profile-img-container">
                    <input type="file" @change="uploadProfileImage" name="profile_image" ref="profile_image" class="d-none" accept=".jpg, .jpeg, .png">
                    <img :src="profile_photo" class="rounded-circle"/>
                    <section class="btn-wrapper">
                        <button class="btn" type="button" @click="$refs.profile_image.click()"><i class="fa-solid fa-pen-to-square"></i></button>
                    </section>
                </div>
                <div class="w-100 invalid-feedback d-block" v-if="studentForm.errors.profile_photo">
                    {{ studentForm.errors.profile_photo }}
                </div>
            </div>
            <div class="col-md-5">
                <Input label="Voornaam" v-model="studentForm.firstname" :error="studentForm.errors.firstname" min="2" required/>
            </div>
            <div class="col-md-7">
                <Input label="Achternaam" v-model="studentForm.lastname" :error="studentForm.errors.lastname" min="2" required/>
            </div>
            <div class="col-md-8">
                <Input label="E-mailadres" v-model="studentForm.email" :error="studentForm.errors.email" type="email" required/>
            </div>
            <div class="col-md-4">
                <Select label="Geslacht" v-model="studentForm.sex" :error="studentForm.errors.sex" required>
                    <option selected disabled></option>
                    <option value="m">Man</option>
                    <option value="v">Vrouw</option>
                    <option value="o">Overig</option>
                </Select>
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
import Select from "@/components/Inputs/Select.vue";
import FormModal from "@/Components/Modals/FormModal.vue";

export default {
    name: 'CreateStudentModal',
    props: {
        openModal: Boolean
    },
    components: {
        FormModal,
        Input,
        Checkbox,
        Select
    },
    data() {
        return {
            studentForm: useForm({
                firstname: null,
                lastname: null,
                email: null,
                sex: null,
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
                profile_photo: null
            }),
            profile_photo: null
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
                    this.profile_photo = null;
                    this.toast('success', 'Succesvol aangemaakt!', 'Student is aangemaakt.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het aanmaken van deze student. Probeer het opnieuw.')
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
            this.studentForm.profile_photo = file;
        }
    }
}
</script>

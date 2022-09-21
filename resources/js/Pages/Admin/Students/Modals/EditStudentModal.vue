<template>
    <FormModal :open="openModal" header="Student bewerken" submit-text="Aanpassen"
               @close="close" @action="editStudent" :disabled="studentForm.processing" v-if="user">
        <form class="row g-3" @submit.prevent="editStudent()">
            <div class="col-md-12 d-flex gap-3 align-items-end mb-1">
                <!-- Profile image -->
                <div class="profile-img-container">
                    <input type="file" @change="uploadProfileImage" name="profile_image" ref="profile_image" class="d-none" accept=".jpg, .jpeg, .png">
                    <img :src="profile_photo ? profile_photo : user.profile_photo_url" class="rounded-circle"/>
                    <section class="btn-wrapper">
                        <button class="btn" type="button" @click="$refs.profile_image.click()"><i class="fa-solid fa-pen-to-square"></i></button>
                    </section>
                </div>
                <!-- Leerlingnummer -->
                <div class="w-100">
                    <label class="form-label" for="leerlingnummer">Leerlingnummer</label>
                    <input type="text" id="leerlingnummer" class="form-control" :value="studentForm.student_id" readonly/>
                </div>
            </div>
            <div class="col-md-12 invalid-feedback d-block" v-if="studentForm.errors.profile_photo">
                {{ studentForm.errors.profile_photo }}
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
import Select from "@/Components/Inputs/Select.vue";

export default {
    name: 'EditStudentModal',
    props: {
        openModal: Boolean,
        user: Object
    },
    components: {
        Input,
        FormModal,
        Select
    },
    data() {
        return {
            studentForm: useForm({
                _method: 'patch',
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
                student_id: null,
                profile_photo: null
            }),
            profile_photo: null
        }
    },
    watch: {
        user: function (newUser) {
            if (newUser != null) {
                this.studentForm.id = newUser.id ?? null;
                this.studentForm.sex = newUser.sex_raw ?? null;
                this.studentForm.firstname = newUser.firstname ?? null;
                this.studentForm.lastname = newUser.lastname ?? null;
                this.studentForm.email = newUser.email ?? null;
                this.studentForm.street = newUser.street ?? null;
                this.studentForm.city = newUser.city ?? null;
                this.studentForm.country = newUser.country ?? null;
                this.studentForm.state = newUser.state ?? null;
                this.studentForm.zipcode = newUser.zipcode ?? null;
                this.studentForm.phone_number = newUser.phone_number ?? null;
                this.studentForm.date_of_birth = newUser.date_of_birth ?? null;
                this.studentForm.student_id = newUser.student_id ?? null;
            }
        }
    },
    methods: {
        close() {
            this.$parent.openEditModal = false;
            this.$parent.activeStudent = null;
            this.studentForm.reset();
            this.profile_photo = null;
        },
        editStudent() {
            this.studentForm.post(route('admin.students.update', this.studentForm.id), {
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
<style lang="scss">
.profile-img-container {
    position: relative;
    width: 120px;
    height: 120px;

    img {
        width: 120px;
        height: 120px;
        opacity: 1;
        transition: opacity .2s ease-in-out;
    }

    .btn-wrapper {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity .2s ease-in-out;

        .btn {
            padding: 8px;
            font-size: 23px;
        }
    }

    &:hover {
        img {
            opacity: .5;
        }
        .btn-wrapper {
            opacity: 1;
        }
    }
}
</style>

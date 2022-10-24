<template>
    <FormModal :open="openModal" header="Docent bewerken" submit-text="Aanpassen"
               @close="close" @action="editTeacher" :disabled="teacherForm.processing" v-if="teacher">
        <form class="row g-3" @submit.prevent="editTeacher()">
            <div class="col-md-12 d-flex gap-3 align-items-end mb-1">
                <!-- Profile image -->
                <div class="profile-img-container">
                    <input type="file" @change="uploadProfileImage" name="profile_image" ref="profile_image" class="d-none" accept=".jpg, .jpeg, .png">
                    <img :src="profile_photo ? profile_photo : teacher.profile_photo_url" class="rounded-circle"/>
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
                    <option value="Man">Man</option>
                    <option value="Vrouw">Vrouw</option>
                    <option value="Overig">Overig</option>
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
                <label for="inputBirthday" class="form-label">Wachtwoord reset</label><br/>
                <button class="btn btn-secondary" type="button" @click="sendPasswordResetLink" :disabled="loadingPasswordResetLink">
                    Verstuur reset link
                    <div class="spinner-border text-light spinner-border-sm" role="status" v-if="loadingPasswordResetLink">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </form>
    </FormModal>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import FormModal from "@/Components/Modals/FormModal.vue";
import Input from "@/Components/Inputs/InputWithLabel.vue";
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
                abbreviation: null,
                student_name: null,
                profile_photo: null
            }),
            profile_photo: null,
            loadingPasswordResetLink: false
        }
    },
    watch: {
        teacher: function (newUser) {
            if (newUser != null) {
                this.teacherForm.id = newUser.id ?? null,
                this.teacherForm.sex = newUser.sex ?? null,
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
            this.teacherForm.post(route('admin.teachers.update', this.teacherForm.id), {
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
        },
        sendPasswordResetLink() {
            this.loadingPasswordResetLink = true;
            axios.post(route('admin.students.reset-password'), {
                email: this.teacher.email
            })
                .then(() => {
                    this.toast('success', 'Successvol!', 'Er is een e-mail verstuurd met instructies om het nieuw wachtwoord in te stellen.');
                })
                .catch(error => {
                    this.toast('error', 'Er is iets fout gegaan!', error.response.data.status[0]);
                })
                .finally(() => {
                    this.loadingPasswordResetLink = false;
                });
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

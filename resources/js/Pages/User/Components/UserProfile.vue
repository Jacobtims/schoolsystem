<template>
    <div class="card">
        <div class="card-body p-4">
            <form @submit.prevent="updateUser()">
                <label class="mb-2">Profielfoto</label>
                <div class="profile-img-container mb-3">
                    <input type="file" @change="uploadProfileImage" name="profile_image" ref="profile_image" class="d-none" accept=".jpg, .jpeg, .png">
                    <img :src="profile_photo ? profile_photo : user.profile_photo_url" class="rounded-circle"/>
                    <section class="btn-wrapper">
                        <button class="btn" type="button" @click="$refs.profile_image.click()"><i class="fa-solid fa-pen-to-square"></i></button>
                    </section>
                </div>
                <Input label="Voornaam" :value="user.firstname" readonly disabled class="mb-3"/>
                <Input label="Achternaam" :value="user.lastname" readonly disabled class="mb-3"/>
                <Input label="E-mailadres" v-model="userForm.email" :error="userForm.errors.email" type="email" required class="mb-4"/>

                <button class="btn btn-success" :disabled="userForm.processing"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
            </form>
        </div>
    </div>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import Input from "@/Components/Inputs/Input.vue";

export default {
    components: {Input},
    props: {
        user: Object
    },
    data() {
        return {
            userForm: useForm({
                _method: 'patch',
                email: this.user.email,
                profile_photo: null
            }),
            profile_photo: null,
        }
    },
    methods: {
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
            this.userForm.profile_photo = file;
        },
        updateUser() {
            this.userForm.post(route('user.profile.update'), {
                onSuccess: () => {
                    this.toast('success', 'Opgeslagen!', 'Je gegevens zijn successvol opgeslagen.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegaan tijdens het opslaan. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>
<style lang="scss">
.profile-img-container {
    position: relative;
    width: 100px;
    height: 100px;

    img {
        width: 100px;
        height: 100px;
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

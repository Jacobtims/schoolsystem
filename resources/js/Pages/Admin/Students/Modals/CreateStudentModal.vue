<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Nieuwe student aanmaken" :draggable="false" :modal="true" @hide="close">
        <div>
            <form class="row g-3" @submit.prevent="createStudent()">
                <div class="col-md-12">
                    <label for="inputStudentId" class="form-label">Leerlingnummer</label><br/>
                    <input type="text" class="form-control" id="inputStudentId" aria-describedby="studentIdHelp" readonly value="?">
                    <div id="studentIdHelp" class="form-text">Leerlingnummer wordt automatisch gegenereerd.</div>
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
                    <label for="inputZip" class="form-label">Postcode</label>
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
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" @click="createStudent" autofocus :disabled="studentForm.processing"><i class="fa-solid fa-check"></i> Aanmaken</button>
            <button class="btn btn-secondary" @click="close" :disabled="studentForm.processing"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {useForm} from "@inertiajs/inertia-vue3";
export default {
    name: 'CreateStudentModal',
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
                date_of_birth: null
            })
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
                },
            })
        }
    }
}
</script>

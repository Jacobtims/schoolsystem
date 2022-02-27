<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Student gegevens" :draggable="false" :modal="true"
            @hide="close" v-if="user">
        <div>
            <form class="row g-3">
                <div class="col-md-12">
                    <label for="inputStudentId" class="form-label">Leerlingnummer</label>
                    <input type="number" class="form-control" id="inputStudentId" :value="user.student.id" readonly>
                </div>
                <div class="col-md-6">
                    <label for="inputFirstname" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" id="inputFirstname" :value="user.firstname">
                </div>
                <div class="col-md-6">
                    <label for="inputLastname" class="form-label">Achternaam</label>
                    <input type="text" class="form-control" id="inputLastname" :value="user.lastname">
                </div>
                <div class="col-md-12">
                    <label for="inputEmail" class="form-label">E-mailadres</label>
                    <input type="text" class="form-control" id="inputEmail" :value="user.email">
                </div>
                <div class="col-md-6">
                    <label for="inputAddress" class="form-label">Straatnaam + huisnummer</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St" :value="user.street">
                </div>
                <div class="col-md-6">
                    <label for="inputCity" class="form-label">Woonplaats</label>
                    <input type="text" class="form-control" id="inputCity" value="Rotterdam">
                </div>
                <div class="col-md-5">
                    <label for="inputCountry" class="form-label">Land</label>
                    <input type="text" class="form-control" id="inputCountry" value="Nederland">
                </div>
                <div class="col-md-5">
                    <label for="inputState" class="form-label">Provincie</label>
                    <input type="text" class="form-control" id="inputState" value="Zuid-Holland">
                </div>
                <div class="col-md-2">
                    <label for="inputZip" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="inputZip" :value="user.zipcode">
                </div>
                <div class="col-md-6">
                    <label for="inputPhone" class="form-label">Telefoonnummer</label>
                    <input type="text" class="form-control" id="inputPhone" :value="user.phone_number">
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Geboortedatum</label>
                    <input type="date" class="form-control" id="inputBirthday" :value="user.date_of_birth">
                </div>
                <div class="col-md-6">
                    <label for="inputBirthday" class="form-label">Wachtwoord reset</label><br/>
                    <small>Verstuurd een link naar e-mailadres <strong>{{ user.email }}</strong> om het wachtwoord mee te kunnen resetten.</small>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-secondary" type="button">Verstuur reset link</button>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-primary" autofocus @click="close"><i class="fa-solid fa-check"></i> Opslaan</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
export default {
    name: 'EditStudentModal',
    props: {
        openModal: Boolean,
        user: Object
    },
    components: {
        Dialog
    },
    methods: {
        close() {
            this.$parent.openEditModal = false;
            this.$parent.activeStudent = null;
        }
    }
}
</script>

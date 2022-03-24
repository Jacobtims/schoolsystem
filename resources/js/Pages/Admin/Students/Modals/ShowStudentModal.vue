<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            :header="user.firstname + ' ' + user.lastname" :draggable="false" :modal="true" @hide="close" v-if="user">
        <div>
            <table class="table table-borderless">
                <tr>
                    <th>Voornaam:</th>
                    <td>{{ user.firstname }}</td>
                </tr>
                <tr>
                    <th>Achternaam:</th>
                    <td>{{ user.lastname }}</td>
                </tr>
                <tr>
                    <th>Leerlingnummer:</th>
                    <td>{{ user.student_id }}</td>
                </tr>
                <tr>
                    <th>E-mailadres:</th>
                    <td><a :href="'mailto: '+user.email" class="p-0">{{ user.email }}</a></td>
                </tr>
                <tr>
                    <th>Telefoonnummer:</th>
                    <td><a :href="'tel: '+user.phone_number" class="p-0">{{ user.phone_number }}</a></td>
                </tr>
                <tr>
                    <th>Adres:</th>
                    <td>{{ user.street }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td>{{ user.zipcode }}, {{ user.city }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td>{{ user.state }} {{ user.country }}</td>
                </tr>
                <tr>
                    <th>Geboortedatum:</th>
                    <td>{{ birthday }}</td>
                </tr>
            </table>
        </div>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import moment from "moment";
export default {
    name: 'ShowStudentModal',
    props: {
        openModal: Boolean,
        user: Object
    },
    components: {
        Dialog
    },
    computed: {
        birthday() {
            return moment(this.user.date_of_birth).format('LL');
        }
    },
    methods: {
        close() {
            this.$parent.openShowModal = false;
            this.$parent.activeStudent = null;
        }
    }
}
</script>

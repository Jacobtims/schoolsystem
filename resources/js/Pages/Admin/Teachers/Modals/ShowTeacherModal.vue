<template>
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            :header="teacher.firstname + ' ' + teacher.lastname" :draggable="false" :modal="true" @hide="close" v-if="teacher">
        <div>
            <table class="table table-borderless">
                <tr>
                    <th>Afkorting:</th>
                    <td>{{ teacher.abbreviation }}</td>
                </tr>
                <tr>
                    <th>Studenten naam:</th>
                    <td>{{ teacher.student_name }}</td>
                </tr>
                <tr>
                    <th>Voornaam:</th>
                    <td>{{ teacher.firstname }}</td>
                </tr>
                <tr>
                    <th>Achternaam:</th>
                    <td>{{ teacher.lastname }}</td>
                </tr>
                <tr>
                    <th>E-mailadres:</th>
                    <td><a :href="'mailto: '+teacher.email" class="p-0">{{ teacher.email }}</a></td>
                </tr>
                <tr>
                    <th>Telefoonnummer:</th>
                    <td><a :href="'tel: '+teacher.phone_number" class="p-0">{{ teacher.phone_number }}</a></td>
                </tr>
                <tr>
                    <th>Adres:</th>
                    <td>{{ teacher.street }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td>{{ teacher.zipcode }}, {{ teacher.city }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td>{{ teacher.state }} {{ teacher.country }}</td>
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
    name: 'ShowTeacherModal',
    props: {
        openModal: Boolean,
        teacher: Object
    },
    components: {
        Dialog
    },
    computed: {
        birthday() {
            return moment(this.teacher.date_of_birth).format('LL');
        }
    },
    methods: {
        close() {
            this.$parent.openShowModal = false;
            this.$parent.activeTeacher = null;
        }
    }
}
</script>

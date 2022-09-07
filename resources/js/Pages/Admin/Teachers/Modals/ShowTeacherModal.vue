<template>
    <ShowModal :open="openModal" header="Persoonlijke informatie" @close="close" v-if="teacher">
        <div class="row">
            <div class="col-3" style="width: 120px;">
                <img :src="teacher.profile_photo_url" alt="Profile picture"
                     class="rounded-circle" width="100" height="100"/>
            </div>
            <div class="col-9">
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
                        <th>Geslacht:</th>
                        <td>{{ sex }}</td>
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
        </div>
    </ShowModal>
</template>
<script>
import Dialog from "primevue/dialog";
import moment from "moment/min/moment-with-locales";
import ShowModal from "@/Components/Modals/ShowModal.vue";
export default {
    name: 'ShowTeacherModal',
    props: {
        openModal: Boolean,
        teacher: Object
    },
    components: {
        ShowModal,
        Dialog
    },
    computed: {
        birthday() {
            return moment(this.teacher.date_of_birth).format('LL');
        },
        sex() {
            let sex = '';
            switch (this.teacher.sex) {
                case "m":
                    sex = "Man"
                    break;
                case "v":
                    sex = "Vrouw"
                    break;
                case "o":
                    sex = "Overig"
                    break;
                default:
                    sex = "-"
                    break;
            }
            return sex;
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

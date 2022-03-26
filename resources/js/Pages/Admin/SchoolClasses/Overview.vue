<template>
    <div class="card">
        <div class="card-body d-flex">
            <multiselect v-model="params.schoolClass" deselect-label="Geselecteerd" track-by="name" label="name" placeholder="Selecteer een klas"
                         :options="schoolClasses" :allow-empty="false" style="max-width: 500px;">
            </multiselect>

            <button class="btn btn-success ms-auto" @click="createSchoolClass"><i class="fa-solid fa-plus"></i> Nieuwe klas</button>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="mb-3 d-flex">
                <h3 class="d-inline">Studenten</h3>
                <button class="btn btn-success ms-auto" @click="addStudentsToSchoolClass" :disabled="!schoolClass">
                    <i class="fa-solid fa-plus"></i> Studenten toevoegen
                </button>
            </div>

            <div class="spinner-border ms-3" role="status" v-if="loading">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="table-responsive" v-else>
                <table class="table table-borderless" id="student-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;"></th>
                            <th style="width: 100px;">Leerlingnummer</th>
                            <th>Naam</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(student, index) in schoolClass.students" :key="'student'+index" v-if="schoolClass && schoolClass.students.length > 0">
                            <td>
                                <img :src="'https://eu.ui-avatars.com/api/?size=50&name='+student.user.firstname+'+'+student.user.lastname" alt="Profile picture"
                                     class="rounded-circle" width="50" height="50"/>
                            </td>
                            <td>{{ student.id }}</td>
                            <td class="text-nowrap">{{ student.user.firstname }} {{ student.user.lastname }}</td>
                            <td>
                                <div class="float-end d-flex">
                                    <button class="btn btn-danger text-nowrap" @click="deleteStudent(student.id)">
                                        <i class="fa-solid fa-trash-can"></i> Uit klas verwijderen
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-else-if="schoolClass">
                            <td colspan="4">Deze klas heeft geen leerlingen!</td>
                        </tr>
                        <tr v-else>
                            <td colspan="4">Selecteer aub een klas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <add-students-modal :open-modal="openAddStudentsModal" :school-class="schoolClass"></add-students-modal>
    <delete-student-from-class-confirmation-modal :open-modal="openDeleteModal" :school-class="schoolClass" :student-id="selectedStudent"></delete-student-from-class-confirmation-modal>
    <create-school-class-modal :open-modal="openCreateModal"></create-school-class-modal>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout";
import AddStudentsModal from "@/Pages/Admin/SchoolClasses/Modals/AddStudentsModal";
import {pickBy, throttle} from "lodash";
import DeleteStudentFromClassConfirmationModal
    from "@/Pages/Admin/SchoolClasses/Modals/DeleteStudentFromClassConfirmationModal";
import CreateSchoolClassModal from "@/Pages/Admin/SchoolClasses/Modals/CreateSchoolClassModal";

export default {
    components: {CreateSchoolClassModal, DeleteStudentFromClassConfirmationModal, AddStudentsModal},
    layout: AdminLayout,
    props: {
        schoolClasses: Object,
        schoolClass: Object
    },
    data() {
        return {
            params: {
                schoolClass: null
            },
            loading: false,
            openAddStudentsModal: false,
            selectedStudent: null,
            openDeleteModal: false,
            openCreateModal: false
        }
    },
    methods: {
        createSchoolClass() {
            this.openCreateModal = true;
        },
        deleteStudent(id) {
            this.selectedStudent = id;
            this.openDeleteModal = true;
        },
        addStudentsToSchoolClass() {
            this.openAddStudentsModal = true;
        }
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('admin.classes.index'), {class: params.schoolClass.name}, { replace: true, preserveState: true, preserveScroll: true });
            }, 150),
            deep: true
        }
    }
}
</script>
<style lang="scss">
#student-table {
    tbody tr td {
        vertical-align: middle;
    }
}

#student-table > tbody > tr > td,
#student-table > tbody > tr > th,
#student-table > tfoot > tr > td,
#student-table > tfoot > tr > th,
#student-table > thead > tr > td,
#student-table > thead > tr > th {
    border-top: 1px solid #E5E5E5;
    border-bottom: 1px solid #E5E5E5;
}
</style>

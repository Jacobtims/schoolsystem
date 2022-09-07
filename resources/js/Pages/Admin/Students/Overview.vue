<template>
    <div>
        <div class="card">
            <div class="card-body d-flex">
                <button class="btn btn-success me-2" @click="createStudent"><i class="fa-solid fa-plus"></i> Nieuwe</button>
                <button class="btn btn-danger" :disabled="!selectedRows || selectedRows.length <= 0" @click="deleteSelectedStudents"><i class="fa-solid fa-trash-can"></i> Verwijderen</button>

                <button class="btn btn-info ms-auto me-2"><i class="fa-solid fa-file-import"></i> Importeren</button>
                <button class="btn btn-dark"><i class="fa-solid fa-file-export"></i> Exporteren</button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex mb-3 px-1">
                    <h3>Leerlingen overzicht</h3>
                    <input type="text" class="form-control ms-auto" placeholder="Zoeken..." v-model="params.search" style="max-width: 400px;"/>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless" id="student-table">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="width: 50px"></th>
                            <th @click="sort('firstname')" class="clickable">
                                <span class="me-2">Voornaam</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'firstname' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'firstname' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('lastname')" class="clickable">
                                <span class="me-2">Achternaam</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'lastname' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'lastname' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('student_id')" class="clickable">
                                <span class="me-2">Leerlingnummer</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'student_id' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'student_id' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('sex')" class="clickable">
                                <span class="me-2">Geslacht</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'sex' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'sex' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('email')" class="clickable">
                                <span class="me-2">E-mailadres</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'email' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'email' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('phone_number')" class="clickable">
                                <span class="me-2">Telefoonnummer</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'phone_number' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'phone_number' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(student, index) in students.data" :key="'student'+index" v-if="students.data.length > 0">
                            <td><input class="form-check-input" type="checkbox" @click="selectRow(student.id)" :checked="selectedRows.indexOf(student.id) > -1"></td>
                            <td>
                                <img :src="student.profile_photo_url" alt="Profile picture"
                                     class="rounded-circle" width="50" height="50"/>
                            </td>
                            <td>{{ student.firstname }}</td>
                            <td>{{ student.lastname }}</td>
                            <td>{{ student.student_id }}</td>
                            <td>{{ sex(student.sex) }}</td>
                            <td>{{ student.email }}</td>
                            <td>{{ student.phone_number }}</td>
                            <td>
                                <div class="float-end d-flex">
                                    <button class="btn btn-warning me-2" @click="showStudent(student)"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-success me-2" @click="editStudent(student)"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger" @click="deleteStudent(student.id)"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="9">Geen studenten gevonden!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <pagination :pagination="students" class="mt-2"></pagination>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <edit-student-modal :open-modal="openEditModal" :user="activeStudent"></edit-student-modal>
    <create-student-modal :open-modal="openCreateModal"></create-student-modal>
    <delete-student-confirmation-modal :open-modal="openDeleteModal" :user-id="deleteId"></delete-student-confirmation-modal>
    <delete-selected-students-confirmation-modal :open-modal="openSelectedDeleteModal" :user-ids="selectedRows"></delete-selected-students-confirmation-modal>
    <show-student-modal :open-modal="openShowModal" :user="activeStudent"></show-student-modal>
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/components/Pagination.vue";
import {pickBy, throttle} from "lodash";
import EditStudentModal from "@/Pages/Admin/Students/Modals/EditStudentModal.vue";
import CreateStudentModal from "@/Pages/Admin/Students/Modals/CreateStudentModal.vue";
import DeleteStudentConfirmationModal from "@/Pages/Admin/Students/Modals/DeleteStudentConfirmationModal.vue";
import DeleteSelectedStudentsConfirmationModal from "@/Pages/Admin/Students/Modals/DeleteSelectedStudentsConfirmationModal.vue";
import ShowStudentModal from "@/Pages/Admin/Students/Modals/ShowStudentModal.vue";

export default {
    layout: AdminLayout,
    components: {
        ShowStudentModal,
        DeleteSelectedStudentsConfirmationModal,
        DeleteStudentConfirmationModal,
        CreateStudentModal,
        EditStudentModal,
        Pagination
    },
    props: {
        students: Object,
        filters: Object
    },
    data() {
        return {
            params: {
                search: this.filters.search,
                field: this.filters.field,
                direction: this.filters.direction
            },
            openEditModal: false,
            activeStudent: null,
            openCreateModal: false,
            openDeleteModal: false,
            deleteId: null,
            selectedRows: [],
            openSelectedDeleteModal: false,
            openShowModal: false
        }
    },
    methods: {
        sort(field) {
            this.params.field = field;
            this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
        },
        editStudent(student) {
            this.activeStudent = student;
            this.openEditModal = true;
        },
        createStudent() {
            this.openCreateModal = true;
        },
        deleteStudent(id) {
            this.deleteId = id;
            this.openDeleteModal = true;
        },
        selectRow(id) {
            if (this.selectedRows.includes(id)) {
                this.selectedRows = this.selectedRows.filter(
                    selectedKeyID => selectedKeyID !== id
                );
            } else {
                this.selectedRows.push(id);
            }
        },
        deleteSelectedStudents() {
            if (this.selectedRows.length > 0) {
                this.openSelectedDeleteModal = true;
            }
        },
        showStudent(student) {
            this.activeStudent = student;
            this.openShowModal = true;
        },
        sex(value) {
            let sex = '';
            switch (value) {
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
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('admin.students.index'), params, { replace: true, preserveState: true, preserveScroll: true });
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

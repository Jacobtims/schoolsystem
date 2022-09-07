<template>
    <div>
        <div class="card">
            <div class="card-body d-flex">
                <button class="btn btn-success me-2" @click="createTeacher"><i class="fa-solid fa-plus"></i> Nieuwe</button>
                <button class="btn btn-danger" :disabled="!selectedRows || selectedRows.length <= 0" @click="deleteSelectedTeachers"><i class="fa-solid fa-trash-can"></i> Verwijderen</button>

                <button class="btn btn-info ms-auto me-2"><i class="fa-solid fa-file-import"></i> Importeren</button>
                <button class="btn btn-dark"><i class="fa-solid fa-file-export"></i> Exporteren</button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex mb-3 px-1">
                    <h3>Docenten overzicht</h3>
                    <input type="text" class="form-control ms-auto" placeholder="Zoeken..." v-model="params.search" style="max-width: 400px;"/>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless" id="teacher-table">
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
                            <th @click="sort('abbreviation')" class="clickable">
                                <span class="me-2">Afkorting</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'abbreviation' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'abbreviation' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('sex')" class="clickable">
                                <span class="me-2">Geslacht</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'sex' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'sex' && params.direction === 'asc'"></i>
                                <i class="fa-solid fa-sort" v-else></i>
                            </th>
                            <th @click="sort('student_name')" class="clickable">
                                <span class="me-2">Studenten naam</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'student_name' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'student_name' && params.direction === 'asc'"></i>
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
                        <tr v-for="(teacher, index) in teachers.data" :key="'teacher'+index" v-if="teachers.data.length > 0">
                            <td><input class="form-check-input" type="checkbox" @click="selectRow(teacher.id)" :checked="selectedRows.indexOf(teacher.id) > -1"></td>
                            <td>
                                <img :src="teacher.profile_photo_url" alt="Profile picture"
                                     class="rounded-circle" width="50" height="50"/>
                            </td>
                            <td>{{ teacher.firstname }}</td>
                            <td>{{ teacher.lastname }}</td>
                            <td>{{ teacher.abbreviation }}</td>
                            <td>{{ teacher.sex }}</td>
                            <td>{{ teacher.student_name }}</td>
                            <td>{{ teacher.email }}</td>
                            <td>{{ teacher.phone_number }}</td>
                            <td>
                                <div class="float-end d-flex">
                                    <button class="btn btn-warning me-2" @click="showTeacher(teacher)"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-success me-2" @click="editTeacher(teacher)"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger" @click="deleteTeacher(teacher.id)"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="9">Geen docenten gevonden!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <pagination :pagination="teachers" class="mt-2"></pagination>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <edit-teacher-modal :open-modal="openEditModal" :teacher="activeTeacher"></edit-teacher-modal>
    <create-teacher-modal :open-modal="openCreateModal"></create-teacher-modal>
    <delete-teacher-confirmation-modal :open-modal="openDeleteModal" :user-id="deleteId"></delete-teacher-confirmation-modal>
    <delete-selected-teachers-confirmation-modal :open-modal="openSelectedDeleteModal" :user-ids="selectedRows"></delete-selected-teachers-confirmation-modal>
    <show-teacher-modal :open-modal="openShowModal" :teacher="activeTeacher"></show-teacher-modal>
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/components/Pagination.vue";
import {pickBy, throttle} from "lodash";
import EditTeacherModal from "@/Pages/Admin/Teachers/Modals/EditTeacherModal.vue";
import CreateTeacherModal from "@/Pages/Admin/Teachers/Modals/CreateTeacherModal.vue";
import DeleteTeacherConfirmationModal from "@/Pages/Admin/Teachers/Modals/DeleteTeacherConfirmationModal.vue";
import DeleteSelectedTeachersConfirmationModal from "@/Pages/Admin/Teachers/Modals/DeleteSelectedTeachersConfirmationModal.vue";
import ShowTeacherModal from "@/Pages/Admin/Teachers/Modals/ShowTeacherModal.vue";

export default {
    layout: AdminLayout,
    components: {
        ShowTeacherModal,
        DeleteSelectedTeachersConfirmationModal,
        DeleteTeacherConfirmationModal,
        CreateTeacherModal,
        EditTeacherModal,
        Pagination
    },
    props: {
        teachers: Object,
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
            activeTeacher: null,
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
        editTeacher(teacher) {
            this.activeTeacher = teacher;
            this.openEditModal = true;
        },
        createTeacher() {
            this.openCreateModal = true;
        },
        deleteTeacher(id) {
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
        deleteSelectedTeachers() {
            if (this.selectedRows.length > 0) {
                this.openSelectedDeleteModal = true;
            }
        },
        showTeacher(teacher) {
            this.activeTeacher = teacher;
            this.openShowModal = true;
        }
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('admin.teachers.index'), params, { replace: true, preserveState: true, preserveScroll: true });
            }, 150),
            deep: true
        }
    }
}
</script>
<style lang="scss">
#teacher-table {
    tbody tr td {
        vertical-align: middle;
    }
}

#teacher-table > tbody > tr > td,
#teacher-table > tbody > tr > th,
#teacher-table > tfoot > tr > td,
#teacher-table > tfoot > tr > th,
#teacher-table > thead > tr > td,
#teacher-table > thead > tr > th {
    border-top: 1px solid #E5E5E5;
    border-bottom: 1px solid #E5E5E5;
}
</style>

<template>
    <div class="card">
        <div class="card-body d-flex">
            <select class="form-select w-50" v-model="selectedClass">
                <option disabled selected value="0">Selecteer een klas</option>
                <option v-for="sClass in schoolClasses" :value="sClass.id">
                    {{ sClass.name }}
                </option>
            </select>

            <button class="btn btn-success ms-auto" @click="createSchoolClass"><i class="fa-solid fa-plus"></i> Nieuwe klas</button>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="mb-3 d-flex">
                <h3 class="d-inline">Leerlingen</h3>
                <button class="btn btn-success ms-auto" @click="createSchoolClass" :disabled="!selectedClass || (students.length > 0 && students[0].length === 0)">
                    <i class="fa-solid fa-plus"></i> Leerling toevoegen</button>
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
                        <tr v-for="(student, index) in students[0]" :key="'student'+index" v-if="selectedClass && students.length > 0 && students[0].length > 0">
                            <td>
                                <img :src="'https://eu.ui-avatars.com/api/?size=50&name='+student.user.firstname+'+'+student.user.lastname" alt="Profile picture"
                                     class="rounded-circle" width="50" height="50"/>
                            </td>
                            <td>{{ student.id }}</td>
                            <td class="text-nowrap">{{ student.user.firstname }} {{ student.user.lastname }}</td>
                            <td>
                                <div class="float-end d-flex">
    <!--                                <button class="btn btn-warning me-2" @click="showStudent(student)"><i class="fa-solid fa-eye"></i></button>-->
    <!--                                <button class="btn btn-success me-2" @click="editStudent(student)"><i class="fa-solid fa-pen-to-square"></i></button>-->
                                    <button class="btn btn-danger text-nowrap" @click="deleteStudent(student.id)">
                                        <i class="fa-solid fa-trash-can"></i> Uit klas verwijderen</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-else-if="selectedClass">
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
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout";

export default {
    layout: AdminLayout,
    props: {
        schoolClasses: Object
    },
    data() {
        return {
            selectedClass: 0,
            students: {},
            loading: false
        }
    },
    methods: {
        createSchoolClass() {

        },
        deleteStudent(id) {

        }
    },
    watch: {
        selectedClass: function (newClass) {
            this.loading = true;
            axios.get(route('admin.classes.show', newClass))
                .then((response) => {
                    this.students = response.data.students;
                })
                .finally(() => {
                    this.loading = false;
                });
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

<template>

    <div>
        <div class="card">
            <div class="card-body d-flex">
                <button class="btn btn-success me-2"><i class="fa-solid fa-plus"></i> Nieuwe</button>
                <button class="btn btn-danger"><i class="fa-solid fa-trash-can"></i> Verwijderen</button>

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
                <table class="table table-borderless" id="student-table">
                    <thead>
                        <tr>
                            <th><input class="form-check-input" type="checkbox"></th>
                            <th style="width: 50px"></th>
                            <th @click="sort('firstname')">
                                <span>Voornaam</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'firstname' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'firstname' && params.direction === 'asc'"></i>
                            </th>
                            <th @click="sort('lastname')">
                                <span>Achternaam</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'lastname' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'lastname' && params.direction === 'asc'"></i>
                            </th>
                            <th @click="sort('student.id')">
                                <span>Leerlingnummer</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'student.id' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'student.id' && params.direction === 'asc'"></i>
                            </th>
                            <th @click="sort('email')">
                                <span>E-mailadres</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'email' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'email' && params.direction === 'asc'"></i>
                            </th>
                            <th @click="sort('phone_number')">
                                <span>Telefoonnummer</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'phone_number' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'phone_number' && params.direction === 'asc'"></i>
                            </th>
                            <th @click="sort('street')">
                                <span>Adres</span>
                                <i class="fa-solid fa-arrow-down-short-wide" v-if="params.field === 'street' && params.direction === 'desc'"></i>
                                <i class="fa-solid fa-arrow-up-wide-short" v-else-if="params.field === 'street' && params.direction === 'asc'"></i>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="student in students.data">
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>
                                <img :src="'https://eu.ui-avatars.com/api/?size=50&name='+student.firstname+'+'+student.lastname" alt="Profile picture"
                                     class="rounded-circle" width="50" height="50"/>
                            </td>
                            <td>{{ student.firstname }}</td>
                            <td>{{ student.lastname }}</td>
                            <td>{{ student.student.id }}</td>
                            <td>{{ student.email }}</td>
                            <td>{{ student.phone_number }}</td>
                            <td>{{ student.street }}</td>
                            <td>
                                <div class="float-end">
                                    <button class="btn btn-success me-2"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <pagination :pagination="students" class="mt-2"></pagination>
            </div>
        </div>
    </div>
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout";
import Pagination from "@/components/Pagination";
import {pickBy, throttle} from "lodash";
export default {
    layout: AdminLayout,
    components: {
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
            }
        }
    },
    methods: {
        sort(field) {
            this.params.field = field;
            this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
        }
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('admin.students.index'), params, { replace: true, preserveState: true });
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

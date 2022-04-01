<template>
    <div>
        <div class="card">
            <div class="card-body d-flex">
                <button class="btn btn-success ms-auto" @click="createSubject">
                    <i class="fa-solid fa-plus"></i> Nieuwe
                </button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex mb-3 px-1">
                    <h3>Alle vakken</h3>
                    <input type="text" class="form-control ms-auto" placeholder="Zoeken..." v-model="params.search" style="max-width: 400px;"/>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless" id="subjects-table">
                        <thead>
                        <tr>
                            <th style="width: 300px;">Naam</th>
                            <th>Afkorting</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(subject, index) in subjects" :key="'subject'+index" :class="{'table-secondary text-secondary': subject.deleted_at}" v-if="subjects.length > 0">
                            <td>{{ subject.name }}</td>
                            <td>{{ subject.abbreviation }}</td>
                            <td>
                                <div class="float-end d-flex">
                                    <button class="btn btn-danger" @click="deleteSubject(subject.id)" v-if="!subject.deleted_at">
                                        <i class="fa-solid fa-trash-can"></i></button>
                                    <button class="btn btn-success" @click="unDeletedSubject(subject.id)" v-else>
                                        <i class="fa-solid fa-arrow-rotate-left"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="9">Geen vakken gevonden!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <create-subject-modal :open-modal="openCreateModal"></create-subject-modal>
    <delete-subject-confirmation-modal :open-modal="openDeleteModal"
                                       :subject-id="deleteId"></delete-subject-confirmation-modal>
    <un-delete-subject-confirmation-modal :open-modal="openUnDeleteModal"
                                          :subject-id="unDeleteId"></un-delete-subject-confirmation-modal>
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout";
import CreateSubjectModal from "@/Pages/Admin/Subjects/Modals/CreateSubjectModal";
import DeleteSubjectConfirmationModal from "@/Pages/Admin/Subjects/Modals/DeleteSubjectConfirmationModal";
import UnDeleteSubjectConfirmationModal from "@/Pages/Admin/Subjects/Modals/UnDeleteSubjectConfirmationModal";
import {pickBy, throttle} from "lodash";

export default {
    layout: AdminLayout,
    components: {
        UnDeleteSubjectConfirmationModal,
        DeleteSubjectConfirmationModal,
        CreateSubjectModal

    },
    props: {
        subjects: Object
    },
    data() {
        return {
            params: {
                search: null
            },
            openCreateModal: false,
            openDeleteModal: false,
            openUnDeleteModal: false,
            deleteId: null,
            unDeleteId: null
        }
    },
    methods: {
        createSubject() {
            this.openCreateModal = true;
        },
        deleteSubject(id) {
            this.deleteId = id;
            this.openDeleteModal = true;
        },
        unDeletedSubject(id) {
            this.unDeleteId = id;
            this.openUnDeleteModal = true;
        }
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('admin.subjects.index'), params, { replace: true, preserveState: true, preserveScroll: true });
            }, 150),
            deep: true
        }
    }
}
</script>
<style lang="scss">
#subjects-table {
    tbody tr td {
        vertical-align: middle;
    }
}

#subjects-table > tbody > tr > td,
#subjects-table > tbody > tr > th,
#subjects-table > tfoot > tr > td,
#subjects-table > tfoot > tr > th,
#subjects-table > thead > tr > td,
#subjects-table > thead > tr > th {
    border-top: 1px solid #E5E5E5;
    border-bottom: 1px solid #E5E5E5;
}
</style>

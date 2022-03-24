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
                <h3 class="mb-3 px-1">All vakken</h3>

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
                        <tr v-for="(subject, index) in subjects" :key="'subject'+index" v-if="subjects.length > 0">
                            <td>{{ subject.name }}</td>
                            <td>{{ subject.abbreviation }}</td>
                            <td>
                                <div class="float-end d-flex">
                                    <button class="btn btn-danger" @click="deleteSubject(subject.id)"><i
                                        class="fa-solid fa-trash-can"></i></button>
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
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout";
import CreateSubjectModal from "@/Pages/Admin/Subjects/Modals/CreateSubjectModal";
import DeleteSubjectConfirmationModal from "@/Pages/Admin/Subjects/Modals/DeleteSubjectConfirmationModal";

export default {
    layout: AdminLayout,
    components: {
        DeleteSubjectConfirmationModal,
        CreateSubjectModal

    },
    props: {
        subjects: Object
    },
    data() {
        return {
            openCreateModal: false,
            openDeleteModal: false,
            deleteId: null
        }
    },
    methods: {
        createSubject() {
            this.openCreateModal = true;
        },
        deleteSubject(id) {
            this.deleteId = id;
            this.openDeleteModal = true;
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

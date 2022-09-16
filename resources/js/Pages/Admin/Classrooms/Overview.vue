<template>
    <div>
        <div class="card">
            <div class="card-body d-flex">
                <button class="btn btn-success ms-auto" @click="createClassroom">
                    <i class="fa-solid fa-plus"></i> Nieuwe
                </button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex mb-3 px-1">
                    <h3>Alle lokalen</h3>
                    <input type="text" class="form-control ms-auto" placeholder="Zoeken..." v-model="params.search" style="max-width: 400px;"/>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless" id="subjects-table">
                        <thead>
                        <tr>
                            <th style="width: 300px;">Naam</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(classroom, index) in classrooms" :key="'subject'+index" :class="{'table-secondary text-secondary': classroom.deleted_at}" v-if="classrooms.length > 0">
                            <td>{{ classroom.name }}</td>
                            <td>
                                <div class="float-end d-flex">
                                    <button class="btn btn-danger" @click="deleteClassroom(classroom.id)" v-if="!classroom.deleted_at">
                                        <i class="fa-solid fa-trash-can"></i></button>
                                    <button class="btn btn-success" @click="unDeletedClassroom(classroom.id)" v-else>
                                        <i class="fa-solid fa-arrow-rotate-left"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="9">Geen lokalen gevonden!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <create-classroom-modal :open-modal="openCreateModal"
    ></create-classroom-modal>
    <delete-classroom-confirmation-modal :open-modal="openDeleteModal"
                                         :classroom-id="deleteId"
    ></delete-classroom-confirmation-modal>
    <restore-classroom-confirmation-modal :open-modal="openRestoreModal"
                                          :classroom-id="restoreId"
    ></restore-classroom-confirmation-modal>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {pickBy, throttle} from "lodash";
import CreateClassroomModal from "@/Pages/Admin/Classrooms/Modals/CreateClassroomModal.vue";
import DeleteClassroomConfirmationModal from "@/Pages/Admin/Classrooms/Modals/DeleteClassroomConfirmationModal.vue";
import RestoreClassroomConfirmationModal from "@/Pages/Admin/Classrooms/Modals/RestoreClassroomConfirmationModal.vue";

export default {
    layout: AdminLayout,
    components: {
        RestoreClassroomConfirmationModal,
        DeleteClassroomConfirmationModal,
        CreateClassroomModal
    },
    props: {
        classrooms: Object
    },
    data() {
        return {
            params: {
                search: null
            },
            openCreateModal: false,
            openDeleteModal: false,
            openRestoreModal: false,
            deleteId: null,
            restoreId: null
        }
    },
    methods: {
        createClassroom() {
            this.openCreateModal = true;
        },
        deleteClassroom(id) {
            this.deleteId = id;
            this.openDeleteModal = true;
        },
        unDeletedClassroom(id) {
            this.restoreId = id;
            this.openRestoreModal = true;
        }
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('admin.classrooms.index'), params, { replace: true, preserveState: true, preserveScroll: true });
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

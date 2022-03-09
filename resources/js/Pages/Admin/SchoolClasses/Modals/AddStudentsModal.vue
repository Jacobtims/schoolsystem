<template>
    <Dialog v-model:visible="openModal" v-if="schoolClass" :breakpoints="{'1200px': '50vw', '992px': '65vw'}"
            :style="{width: '40vw'}" :header="'Studenten toevoegen aan ' + schoolClass.name" :draggable="false" :modal="true" @hide="close">
        <div>
            <h5>Leerlingnummer</h5>
            <div class="d-flex mb-3" v-for="(id, index) in studentIds" :key="'studentid'+index">
                <input class="form-control me-2" type="number" v-model="studentIds[index]"/>
                <button class="btn btn-outline-danger" @click="removeId(index)" :disabled="studentIds.length <= 1">
                    <i class="fa-solid fa-trash-can"></i></button>
            </div>
            <div class="d-flex">
                <button class="btn btn-sm btn-outline-success me-3" @click="addIdRow">
                    <i class="fa-solid fa-plus"></i> Nieuwe toevoegen
                </button>
                <button class="btn btn-sm btn-outline-primary" @click="addMultiple">Meerdere toevoegen</button>
            </div>
        </div>
        <template #footer>
            <button class="btn btn-primary" type="submit" autofocus @click="addStudentsToClass"><i class="fa-solid fa-check"></i> Toevoegen</button>
            <button class="btn btn-secondary" @click="close"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>

        <!-- Add multiple modal -->
        <Dialog v-model:visible="showMultipleModal" :breakpoints="{'1200px': '48vw', '992px': '61vw'}"
                :style="{width: '35vw'}" :modal="true" :draggable="false" @hide="closeMultipleModal">
            <h5 class="mb-0">Leerlingnummers</h5>
            <small>Gescheiden door komma's (,)</small>
            <textarea class="form-control" rows="5" v-model="multipleIds" placeholder="Voorbeeld: 1,2,3,4,5"></textarea>

            <template #footer>
                <button class="btn btn-sm btn-success" @click="addMultipleIds">
                    <i class="fa-solid fa-check"></i> Studenten toevoegen
                </button>
                <button class="btn btn-sm btn-secondary" @click="closeMultipleModal">
                    <i class="fa-solid fa-xmark"></i> Annuleren
                </button>
            </template>
        </Dialog>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";

export default {
    name: 'AddStudentsModal',
    props: {
        openModal: Boolean,
        schoolClass: Object
    },
    components: {
        Dialog
    },
    data() {
        return {
            studentIds: [null],
            showMultipleModal: false,
            multipleIds: null
        }
    },
    methods: {
        close() {
            this.$parent.openAddStudentsModal = false;
            this.studentIds = [null]
        },
        removeId(id) {
            this.studentIds.splice(id, 1);
        },
        addIdRow() {
            this.studentIds.push(null);
        },
        addMultiple() {
            this.showMultipleModal = true;
        },
        closeMultipleModal() {
            this.showMultipleModal = false;
            this.multipleIds = null;
        },
        addMultipleIds() {
            const idsArray = this.multipleIds.split(",");
            idsArray.forEach((id) => {
                this.studentIds.push(id.replace(/ /g, '')); // Remove white spaces and push to studentsIds array
            });
            this.closeMultipleModal();
        },
        addStudentsToClass() {
            this.$inertia.post(route('admin.classes.addStudents', this.schoolClass.id), {
                studentIds: this.studentIds
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.close();
                },
            })
        }
    }
}
</script>

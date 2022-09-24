<template>
    <FormModal :open="openModal" :header="'Studenten toevoegen aan klas ' + schoolClass.name" submit-text="Toevoegen"
               @close="close" @action="addStudentsToClass" :disabled="disabled" v-if="schoolClass">
        <!-- List with students -->
        <h5>Selecteer leerlingen</h5>

        <multiselect v-model="selectedStudents" label="id" track-by="id" placeholder="Type om te zoeken..."
                     open-direction="bottom" :options="students" :multiple="true" :searchable="true" :loading="isLoading"
                     :internal-search="false" :clear-on-select="false" :close-on-select="false" :options-limit="50"
                     :limit="3" :limit-text="limitText" :max-height="400" :hide-selected="true"
                     @search-change="asyncFindStudents" :custom-label="customLabel" :show-labels="false">
            <template v-slot:noResult>
                Geen studenten gevonden. Verander je zoekopdracht.
            </template>
            <template v-slot:noOptions>
                Geen studenten.
            </template>
        </multiselect>
    </FormModal>
</template>
<script>
import FormModal from "@/components/Modals/FormModal.vue";

export default {
    name: 'AddStudentsModal',
    props: {
        openModal: Boolean,
        schoolClass: Object
    },
    components: {
        FormModal
    },
    data() {
        return {
            disabled: false,
            selectedStudents: [],
            students: [],
            isLoading: false
        }
    },
    mounted() {
        this.asyncFindStudents();
    },
    methods: {
        asyncFindStudents (query) {
            this.isLoading = true;
            axios.get(route('admin.classes.get-students'), {
                params: {
                    query: query,
                    school_class: this.schoolClass.id
                }
            })
                .then(response => {
                    this.students = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        limitText (count) {
            return `en ${count} andere studenten`
        },
        customLabel ({id, firstname, lastname}) {
            return `${id} – ${firstname} ${lastname}`
        },
        close() {
            this.$parent.openAddStudentsModal = false;
        },
        addStudentsToClass() {
            this.$inertia.post(route('admin.classes.addStudents', this.schoolClass.id), {
                students: this.selectedStudents
            }, {
                preserveScroll: true,
                onBefore: () => {
                    this.disabled = true;
                },
                onSuccess: () => {
                    this.close();
                    this.selectedStudents = [];
                    this.asyncFindStudents();
                    this.toast('success', 'Succesvol toegevoegd!', 'Studenten zijn toegevoegd aan deze klas.')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het toevoegen van deze studenten. Probeer het opnieuw.')
                },
                onFinish: () => {
                    this.disabled = false;
                }
            })
        }
    }
}
</script>
<style>
.p-dialog-content {
    overflow-y: visible;
}
</style>

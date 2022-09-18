<template>
    <ShowModal :open="openModal" header="Exporteer studenten" @close="close">
        <p class="text-danger">Let op! Dit kan enige tijd duren.</p>

        <button class="btn btn-success" @click="exportStudents" :disabled="loading">
            Exporteer
            <div class="spinner-border spinner-border-sm text-light" v-if="loading">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </ShowModal>
</template>
<script>
import ShowModal from "@/Components/Modals/ShowModal.vue";

export default {
    components: {ShowModal},
    props: {
        openModal: Boolean,
    },
    data() {
        return {
            loading: false,
        }
    },
    methods: {
        close() {
            this.$parent.openExportModal = false;
        },
        exportStudents() {
            this.loading = true;
            axios.get(route('admin.students.export'), { responseType: 'blob' })
                .then(response => {
                    const blob = new Blob([response.data], { type: 'application/xlsx' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = `Students_${this.$dayjs().format('DD-MM-YYYY')}.xlsx`;
                    link.click();
                    URL.revokeObjectURL(link.href);

                    this.close();
                })
                .catch(() => {
                    this.toast('error', 'Er is iets fout gegaan!', 'Er is iets fout gegaan tijdens het downloaden! Probeer het later nog eens.');
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
}
</script>

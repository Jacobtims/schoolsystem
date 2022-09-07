<template>
    <ShowModal :open="openModal" header="&nbsp;" @close="close">
        <table class="table table-borderless" id="table-grades">
            <tr>
                <td><strong>Vak:</strong></td>
                <td>{{ grade.assignment.subject.name }}</td>
            </tr>
            <tr>
                <td><strong>Cijfer:</strong></td>
                <td :class="{'grade-plus': grade.number > 5.4, 'grade-min': grade.number < 5.5}">
                    {{ grade.number }}
                </td>
            </tr>
            <tr>
                <td><strong>Toets:</strong></td>
                <td>{{ grade.assignment.name }}</td>
            </tr>
            <tr>
                <td><strong>Omschrijving:</strong></td>
                <td>{{ grade.assignment.description }}</td>
            </tr>
            <tr>
                <td><strong>Weging:</strong></td>
                <td>{{ grade.assignment.weighting }}&times;</td>
            </tr>
            <tr>
                <td><strong>Datum:</strong></td>
                <td>{{ grade.date }}</td>
            </tr>
            <tr>
                <td><strong>Docent:</strong></td>
                <td>{{ grade.teacher.student_name }}</td>
            </tr>
        </table>
    </ShowModal>
</template>

<script>
import ShowModal from "@/Components/Modals/ShowModal.vue";

export default {
    name: 'ShowGradeModal',
    props: {
        openModal: Boolean,
        grade: Object
    },
    components: {
        ShowModal
    },
    methods: {
        close() {
            this.$parent.openModal = false;
            this.$parent.activeGrade = null;
        }
    }
}
</script>

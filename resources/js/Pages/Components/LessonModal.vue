<template>
    <!-- Modal -->
    <ShowModal :open="openModal" :header="lesson ? lesson.subject.name : 'Informatie ophalen...'" @close="close">
        <div class="text-center my-5" v-if="!lesson">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else>
            <table class="table table-borderless" id="table-grades">
                <tr>
                    <td><strong>Klas:</strong></td>
                    <td>{{ lesson.school_class.name }}</td>
                </tr>
                <tr>
                    <td><strong>Datum:</strong></td>
                    <td>{{ $dayjs(lesson.date).format('LL') }}</td>
                </tr>
                <tr>
                    <td><strong>Tijd:</strong></td>
                    <td>{{ lesson.time.from }} - {{ lesson.time.to }}</td>
                </tr>
                <tr>
                    <td><strong>Docent:</strong></td>
                    <td>{{ lesson.teacher.abbreviation }} ({{ lesson.teacher.student_name }})</td>
                </tr>
                <tr>
                    <td><strong>Lokaal:</strong></td>
                    <td>{{ lesson.classroom.name }}</td>
                </tr>
            </table>
            <div v-if="!lesson.deleted && lesson.homework && lesson.homework.length > 0">
                <hr/>
                <table class="table table-borderless">
                    <tr v-for="hw in lesson.homework">
                        <td v-if="hw.type === 'activity'" title="Activiteit" style="width: 30px;"><i class="fa-solid fa-person-running"></i></td>
                        <td v-else-if="hw.type === 'test'" title="Toets" style="width: 30px;"><i class="fa-solid fa-pencil"></i></td>
                        <td v-else title="Huiswerk" style="width: 30px;"><i class="fa-solid fa-book"></i></td>
                        <td style="word-break: break-all; width: 350px;">{{ hw.description }}</td>
                        <td class="text-end">{{ $dayjs(hw.created_at).format('LLL') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </ShowModal>
</template>
<script>
import Dialog from "primevue/dialog";
import ShowModal from "@/Components/Modals/ShowModal.vue";

export default {
    props: {
        openModal: Boolean,
        lessonId: [Number, String],
    },
    components: {
        ShowModal,
        Dialog,
    },
    data() {
        return {
            lesson: null
        }
    },
    methods: {
        close() {
            this.$parent.openModal = false;
            this.$parent.lessonId = null;
            this.lesson = null;
        }
    },
    watch: {
        lessonId: function (id) {
            if (id === null) return;
            axios.get(this.route('student.schedules.get-lesson', id))
                .then((response) => {
                    this.lesson = response.data;
                })
                .catch(() => {
                    this.toast('error', 'Er is iets fout gegaan bij het ophalen van dit lesuur!');
                })
        }
    }
}
</script>

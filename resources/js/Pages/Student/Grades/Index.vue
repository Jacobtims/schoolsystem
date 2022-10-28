<template>
    <Card v-for="(subject, index) in subjects" class="mb-6 flex flex-col md:flex-row md:items-center"
          :key="'subject'+index" v-if="subjects && subjects.length > 0">
        <h3 class="text-2xl font-medium w-48 overflow-x-auto mb-4 md:mb-0">{{ subject.name }}</h3>
        <div class="flex flex-1 items-center justify-between">
            <div class="flex overflow-x-auto">
                <div v-for="grade in subject.grades"
                     class="p-2 mr-4 text-center text-lg bg-gray-200 rounded-lg w-11 cursor-pointer"
                     :class="gradeClass(grade.number)"
                     @click="openGradeModal(grade)">
                    {{ grade.number }}
                </div>
            </div>
            <div class="p-2 text-center text-lg bg-gray-200 rounded-lg w-11" :class="gradeClass(subject.average)">
                {{ subject.average }}
            </div>
        </div>
    </Card>
    <Card class="mb-3" v-else>
        Er zijn geen cijfers gevonden!
    </Card>

    <!-- Modal -->
    <show-grade-modal :open-modal="openModal" :grade="activeGrade"/>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout.vue";
import ShowGradeModal from "@/Pages/Student/Grades/Modals/ShowGradeModal.vue";
import Card from '@/Components/Card.vue';

export default {
    layout: StudentLayout,
    props: {
        subjects: Object
    },
    components: {
        ShowGradeModal,
        Card
    },
    data() {
        return {
            activeGrade: null,
            openModal: false
        }
    },
    mounted() {
        this.calculateAverages();
    },
    methods: {
        openGradeModal(grade) {
            this.activeGrade = grade;
            this.activeGrade.date = this.$dayjs(grade.created_at).format('LL');
            this.openModal = true;
        },
        gradeClass(grade) {
            if (grade >= 5.5) {
                return "text-dark-green";
            }
            return "text-dark-red";
        },
        calculateAverages() {
            this.subjects.forEach(subject => {
                let count = 0;
                let total = 0;
                subject.total = subject.grades.forEach(grade => {
                    count += grade.assignment.weighting;
                    total += (grade.number * grade.assignment.weighting);
                });
                subject.average = total / count;
            });
        }
    }
}
</script>

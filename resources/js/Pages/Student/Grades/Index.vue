<template>
    <div class="card mb-3" v-for="(subject, index) in subjects" :key="'subject'+index"
         v-if="subjects && subjects.length > 0">
        <div class="card-body row">
            <h3 class="d-inline col-2">{{ subject.name }}</h3>
            <div class="col-9 row" id="grades">
                <div v-for="grade in subject.grades"
                     class="grade clickable" :class="gradeClass(grade.number)"
                     @click="openGradeModal(grade)">
                    {{ grade.number }}
                </div>
            </div>
            <div class="col-1 average" :class="gradeClass(subject.average)">
                {{ subject.average }}
            </div>
        </div>
    </div>
    <div class="card mb-3" v-else>
        <div class="card-body">
            Er zijn geen cijfers gevonden!
        </div>
    </div>

    <!-- Modal -->
    <show-grade-modal :open-modal="openModal" :grade="activeGrade"/>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout.vue";
import ShowGradeModal from "@/Pages/Student/Grades/Modals/ShowGradeModal.vue";

export default {
    layout: StudentLayout,
    props: {
        subjects: Object
    },
    components: {
        ShowGradeModal
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
                return "grade-plus";
            }
            return "grade-min";
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
<style lang="scss">
.grade, .average {
    padding: 8px;
    margin-right: 16px;
    margin-bottom: 16px;
    width: 42px;
    height: 42px;
    text-align: center;
    background-color: #E5E5E5;
    border-radius: 8px;
    font-size: 17px;
    font-weight: 600;
}

.average {
    position: absolute;
    right: 0;
}

#grades {
    margin-bottom: -16px;
}

.grade-plus {
    color: #00E60F;
}

.grade-min {
    color: #E60000;
}

#table-grades {
    font-size: 18px;
}
</style>

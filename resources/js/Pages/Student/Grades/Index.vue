<template>
    <div class="card mb-3" v-for="(subject, index) in grades" :key="index" v-if="grades && Object.keys(grades).length > 0">
        <div class="card-body row">
            <h3 class="d-inline col-2">{{ subject[0]['assignment']['subject']['name'] }}</h3>
            <div class="col-9 row" id="grades">
                <div v-for="grade in subject"
                     :class="{'grade': true, 'clickable': true, 'grade-plus': grade.number > 5.4, 'grade-min': grade.number < 5.5}"
                     @click="openGradeModal(grade)">
                    {{ grade.number }}
                </div>
            </div>
            <div :class="{'col-1': true, 'average': true, 'grade-plus': averages[index] > 5.4, 'grade-min': averages[index] < 5.5}">
                {{ averages[index] }}
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
import Dialog from 'primevue/dialog';
import ShowGradeModal from "@/Pages/Student/Grades/Modals/ShowGradeModal.vue";

export default {
    layout: StudentLayout,
    props: {
        grades: Object,
        averages: Object
    },
    components: {
        ShowGradeModal,
        Dialog
    },
    data() {
        return {
            activeGrade: null,
            openModal: false
        }
    },
    methods: {
        openGradeModal(grade) {
            this.activeGrade = grade;
            this.activeGrade.date = this.$dayjs(grade.created_at).format('LL');
            this.openModal = true;
        },
        closeGradeModal() {
            this.activeGrade = null;
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

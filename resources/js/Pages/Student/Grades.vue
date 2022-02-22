<template>
    <student-layout>
        <div class="card mb-3" v-for="(subject, index) in grades" :key="index">
            <div class="card-body row">
                <h3 class="d-inline col-2">{{ subject[0]['subject']['name'] }}</h3>
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

        <!-- Modal -->
        <Dialog v-model:visible="openModal" :breakpoints="{'960px': '75vw'}" :style="{width: '50vw'}" header="&nbsp;"
                :draggable="false" :modal="true" :dismissableMask="true" @hide="closeGradeModal">
            <table class="table table-borderless" id="table-grades">
                <tr>
                    <td><strong>Vak:</strong></td>
                    <td>{{ activeGrade.subject.name }}</td>
                </tr>
                <tr>
                    <td><strong>Cijfer:</strong></td>
                    <td :class="{'grade-plus': activeGrade.number > 5.4, 'grade-min': activeGrade.number < 5.5}">
                        {{ activeGrade.number }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Omschrijving:</strong></td>
                    <td>{{ activeGrade.description }}</td>
                </tr>
                <tr>
                    <td><strong>Weging:</strong></td>
                    <td>{{ activeGrade.weighting }}&times;</td>
                </tr>
                <tr>
                    <td><strong>Datum:</strong></td>
                    <td>{{ activeGrade.date }}</td>
                </tr>
                <tr>
                    <td><strong>Docent:</strong></td>
                    <td>{{ activeGrade.teacher.student_name }}</td>
                </tr>
            </table>
        </Dialog>
    </student-layout>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout";
import Dialog from 'primevue/dialog';

export default {
    props: {
        grades: Object,
        averages: Object
    },
    components: {
        StudentLayout,
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
            this.activeGrade.date = this.$moment(grade.created_at).format('LL');
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

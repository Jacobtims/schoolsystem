<template>
    <student-layout>
        <div class="card mb-3" v-for="(subject, index) in grades" :key="index">
            <div class="card-body row">
                <h3 class="d-inline col-2">{{ subject[0]['subject']['name'] }}</h3>
                <div v-for="grade in subject"
                     :class="{'col-10': true, 'grade': true, 'clickable': true, 'grade-plus': grade.number > 5.4, 'grade-min': grade.number < 5.5}"
                     data-bs-toggle="modal" data-bs-target="#exampleModal" @click="openGradeModal(grade)">
                    {{ grade.number }}
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" ref="exampleModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" v-if="activeGrade">
                        <table class="table table-borderless" id="table-grades">
                            <tr>
                                <td><strong>Vak:</strong></td>
                                <td>{{ activeGrade.subject.name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cijfer:</strong></td>
                                <td :class="{'grade-plus': activeGrade.number > 5.4, 'grade-min': activeGrade.number < 5.5}">{{ activeGrade.number }}</td>
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
                                <td>???</td>
                            </tr>
                        </table>
                    </div>
<!--                    <div class="modal-footer">-->
<!--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
<!--                        <button type="button" class="btn btn-primary">Save changes</button>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </student-layout>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout";

export default {
    props: {
        grades: Object
    },
    components: {
        StudentLayout
    },
    data() {
        return {
            activeGrade: null
        }
    },
    methods: {
        openGradeModal(grade) {
            this.activeGrade = grade;
            this.activeGrade.date = this.$moment(grade.created_at).format('LL');
        }
    }
}
</script>
<style lang="scss">
.grade {
    padding: 8px;
    margin-right: 16px;
    width: 42px;
    text-align: center;
    background-color: #E5E5E5;
    border-radius: 8px;
    font-size: 17px;
    font-weight: 600;
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

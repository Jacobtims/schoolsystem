<template>
    <div v-if="subjects.length > 0">
        <div class="d-flex mb-3">
            <Link :href="route('teacher.grades')" as="button" type="button" class="btn btn-secondary"><i class="fa-solid fa-angles-left"></i> Terug</Link>
            <button class="btn btn-success ms-auto" @click="saveGrades"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item" v-for="subject in subjects" @click="changeSubject(subject.name)">
                <a class="nav-link" href="#" :class="{'active': subject.name === params.subject}">{{ subject.name }}</a>
            </li>
        </ul>
        <div class="table-responsive">
            <table class="table bg-white table-bordered" id="teacherGradeTable">
                <thead>
                <tr>
                    <th style="width: 10px;">Leerling-nummer</th>
                    <th style="width: 10%;" class="border-0 border-end border-2 border-dark">Naam</th>
                    <th v-for="assignment in assignments" class="name" v-if="assignments.length > 0" :title="assignment.description">{{ assignment.name }}</th>
                    <th><button class="btn btn-sm btn-success" @click="createAssignment"><i class="fa-solid fa-plus"></i> Toets toevoegen</button></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(student, index) in schoolClass.students" :key="'classStudent'+index" v-if="schoolClass && schoolClass.students && schoolClass.students.length > 0">
                    <td>{{ student.id }}</td>
                    <td class="border-0 border-end border-2 border-dark">{{ student.user.firstname }} {{ student.user.lastname }}</td>
                    <template v-for="assignment in assignments" v-if="assignments.length > 0">
                        <td v-if="grades[assignment.id] && grades[assignment.id][student.id]" class="grade">
                            <input type="number" v-model="grades[assignment.id][student.id].number" min="1" max="10" step="0.1">
                        </td>
                        <td v-else class="grade">
                            <input type="number" v-model="grades[assignment.id][student.id].number" min="1" max="10" step="0.1" v-if="grades[assignment.id]">
                            <input type="number" disabled value="-1" v-else>
                        </td>
                    </template>
                    <td></td>
                </tr>
                <tr v-else>
                    <td colspan="3">Geen studenten gevonden!</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex">
            <Link :href="route('teacher.grades')" as="button" type="button" class="btn btn-secondary"><i class="fa-solid fa-angles-left"></i> Terug</Link>
            <button class="btn btn-success ms-auto" @click="saveGrades"><i class="fa-solid fa-floppy-disk"></i> Opslaan</button>
        </div>
    </div>
    <div v-else>
        <p>Geen vakken gevonden!</p>
    </div>

    <create-assignment-modal :open-modal="openCreateModal" :subject="subject" :school-class="schoolClass.name"></create-assignment-modal>
</template>
<script>
import {pickBy} from "lodash";
import TeacherLayout from "@/Layouts/TeacherLayout";
import CreateAssignmentModal from "@/Pages/Teacher/Grades/Modals/CreateAssignmentModal";
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {CreateAssignmentModal, Link},
    layout: TeacherLayout,
    props: {
        subjects: Object,
        subject: String,
        schoolClass: Object,
        assignments: Object
    },
    data() {
        return {
            params: {
                subject: this.subject
            },
            grades: {},
            openCreateModal: false,

        }
    },
    watch: {
        params: {
            handler() {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('teacher.grades.show', this.schoolClass.name), params, {
                    replace: true,
                    preserveState: true,
                    preserveScroll: true
                })
            },
            deep: true
        },
        assignments: {
            handler() {
                this.getGrades();
            }
        }
    },
    mounted() {
        this.getGrades();
    },
    methods: {
        changeSubject(subject) {
            this.params.subject = subject;
        },
        getGrades() {
            this.grades = {};
            this.assignments.forEach((assignment) => {
                this.grades[assignment.id] = {};
                assignment.grades.forEach((grade) => {
                    this.grades[assignment.id][grade.student_id] = grade;
                })
            })
            this.addGradesForMissingStudents();
        },
        addGradesForMissingStudents() {
            this.schoolClass.students.forEach((student) => {
                this.assignments.forEach((assignment) => {
                    if(!this.grades[assignment.id][student.id]) {
                        this.grades[assignment.id][student.id] = {
                            number: null
                        };
                    }
                })
            })

        },
        createAssignment() {
            this.openCreateModal = true;
        },
        saveGrades() {
            this.$inertia.post(route('teacher.grades.store'), {
                grades: this.grades
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.toast('success', 'Succesvol opgeslagen!', 'Alle cijfers zijn opgeslagen!')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het opslaan van deze cijfers. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>
<style lang="scss">
#teacherGradeTable {
    tbody tr td.grade {
        text-align: center;
        vertical-align: middle;
        padding: 0;
        height: 40px;
        width: 120px;

        input[type=number] {
            display: inline-block;
            width: 100%;
            height: 100%;
            text-align: center;
            border: none;
            background-color: #fff;
            font-size: 16px;
            padding: 0;
        }
    }

    thead tr th.name {
        text-align: center;
    }
}
</style>

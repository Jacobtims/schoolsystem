<template>
    <h2 class="mb-3">Rooster uren uitplannen</h2>
    <form class="row g-3" @submit.prevent="deleteLessons()">
        <div class="col-md-12">
            <label for="multiselectStudents" class="form-label">Student(en)</label>
            <input type="text" class="form-control">
        </div>
        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="check-permanent">
                <label class="form-check-label" for="check-permanent">
                    Permanent verwijderen
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-danger" type="submit" :disabled="lessonForm.processing">
                <i class="fa-solid fa-trash"></i> Verwijderen
            </button>
        </div>
    </form>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import {debounce} from "lodash";
import moment from "moment/moment";

export default {
    name: "delete-lessons",
    data() {
        return {
            lessonForm: useForm({
                students: [],
                teacher: [],
                subject: [],
                date: null,
                lessons: []
            }),
            students: [],
            isLoadingStudents: false,
            teachers: [],
            isLoadingTeachers: false,
            subjects: [],
            isLoadingSubjects: false
        }
    },
    watch: {
        createdRecords(newValue) {
            if (newValue !== null) {
                this.toast('success', 'Succesvol toegevoegd!', 'In totaal '+(newValue ?? 0)+' uren toegevoegd!');
            }
        }
    },
    computed: {
        yesterday: () => {
            return moment().format('YYYY-MM-DD');
        }
    },
    methods: {
        deleteLessons() {
            this.lessonForm.post(route('admin.schedules.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.lessonForm.reset();
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het toevoegen van de uren.')
                }
            })
        },
        studentLabel(student) {
            return `${student.id} - ${student.user.firstname} ${student.user.lastname}`
        },
        limitTextStudent(count) {
            return `En ${count} andere student${(count === 1 ? '' : 'en')}`
        },
        asyncFindStudents: debounce(function (query) {
            this.isLoadingStudents = true
            axios.get(this.route('admin.schedules.getStudents', {
                query: query
            }))
                .then((response) => {
                    this.students = response.data
                    this.isLoadingStudents = false
                })
        }, 150),
        teacherLabel(teacher) {
            return `${teacher.abbreviation} - ${teacher.user.firstname} ${teacher.user.lastname}`
        },
        asyncFindTeachers: debounce(function (query) {
            this.isLoadingTeachers = true
            axios.get(this.route('admin.schedules.getTeachers', {
                query: query
            }))
                .then((response) => {
                    this.teachers = response.data
                    this.isLoadingTeachers = false
                })
        }, 150),
        asyncFindSubjects: debounce(function (query) {
            this.isLoadingSubjects = true
            axios.get(this.route('admin.schedules.getSubjects', {
                query: query
            }))
                .then((response) => {
                    this.subjects = response.data
                    this.isLoadingSubjects = false
                })
        }, 150)
    },
}
</script>

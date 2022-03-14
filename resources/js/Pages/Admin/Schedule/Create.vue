<template>
    <h2 class="mb-3">Rooster uren aanmaken</h2>
    <form class="row g-3" @submit.prevent="createLessons()">
        <div class="col-md-12">
            <label for="multiselectStudents" class="form-label">Student(en)</label>
            <multiselect v-model="lessonForm.students" id="multiselectStudents" :custom-label="studentLabel" track-by="id"
                         placeholder="Type om studenten te zoeken" open-direction="bottom" :options="students" :multiple="true"
                         :searchable="true" :loading="isLoadingStudents" :internal-search="false" :clear-on-select="false" :close-on-select="false"
                         :options-limit="300" :limit="5" :limit-text="limitTextStudent" :max-height="600" :show-no-results="true"
                         @search-change="asyncFindStudents" aria-describedby="feedbackMultiselectStudents" :class="{'is-invalid': lessonForm.errors.students}">

                <template v-slot:noResult>Oops! Geen studenten gevonden. Verander je zoekopdracht.</template>
                <template v-slot:noOptions>Oops! Geen studenten gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectStudents" class="invalid-feedback" v-if="lessonForm.errors.students">
                {{ lessonForm.errors.students }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="multiselectTeacher" class="form-label">Docent</label>
            <multiselect v-model="lessonForm.teacher" id="multiselectTeacher" :custom-label="teacherLabel" track-by="abbreviation"
                         placeholder="Type om een docent te zoeken" open-direction="bottom" :options="teachers"
                         :searchable="true" :loading="isLoadingTeachers" :internal-search="false" :options-limit="300"
                         :show-no-results="true" @search-change="asyncFindTeachers" aria-describedby="feedbackMultiselectTeacher"
                         :class="{'is-invalid': lessonForm.errors.teacher}">

                <template v-slot:noResult>Oops! Geen docenten gevonden. Verander je zoekopdracht.</template>
                <template v-slot:noOptions>Oops! Geen docenten gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectTeacher" class="invalid-feedback" v-if="lessonForm.errors.teacher">
                {{ lessonForm.errors.teacher }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="multiselectSubject" class="form-label">Vak</label>
            <multiselect v-model="lessonForm.subject" id="multiselectSubject" label="name" track-by="id"
                         placeholder="Type om een vak te zoeken" open-direction="bottom" :options="subjects"
                         :searchable="true" :loading="isLoadingSubjects" :internal-search="false" :options-limit="300"
                         :show-no-results="true" @search-change="asyncFindSubjects" aria-describedby="feedbackMultiselectSubject"
                         :class="{'is-invalid': lessonForm.errors.subject}">

                <template v-slot:noResult>Oops! Geen vakken gevonden. Verander je zoekopdracht.</template>
                <template v-slot:noOptions>Oops! Geen vakken gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectSubject" class="invalid-feedback" v-if="lessonForm.errors.subject">
                {{ lessonForm.errors.subject }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="selectDate" class="form-label">Datum</label>
            <input class="form-control" type="date" id="selectDate" v-model="lessonForm.date" :min="yesterday"
                   :class="{'is-invalid': lessonForm.errors.date}" aria-describedby="feedbackSelectDate"/>
            <div id="feedbackSelectDate" class="invalid-feedback" v-if="lessonForm.errors.date">
                {{ lessonForm.errors.date }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="multiselectLessons" class="form-label">Lesuren</label>
            <multiselect v-model="lessonForm.lessons" id="multiselectLessons" label="id" track-by="id"
                         placeholder="Selecteer lesuren" open-direction="bottom" :options="lessons" :multiple="true"
                         :clear-on-select="false" :close-on-select="false" :options-limit="300" :show-no-results="true"
                         aria-describedby="feedbackMultiselectLessons" :class="{'is-invalid': lessonForm.errors.lessons}">

                <template v-slot:noResult>Oops! Dit lesuur bestaat niet.</template>
                <template v-slot:noOptions>Oops! Geen lesuren gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectLessons" class="invalid-feedback" v-if="lessonForm.errors.lessons">
                {{ lessonForm.errors.lessons }}
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-success" type="submit" :disabled="lessonForm.processing">
                <i class="fa-solid fa-plus"></i> Toevoegen
            </button>
        </div>
    </form>
    <div class="card card-body mt-3" v-if="newCreated">
        <h3>Succesvol toegevoegd!</h3>
        <small>In totaal {{ newCreated.length ?? 0 }} uren toegevoegd!</small>
        <div v-for="record in newCreated">
            <br/>
            <ul>
                <li>Student: {{ record.student }}</li>
                <li>Docent: {{ record.teacher }}</li>
                <li>Vak: {{ record.subject }}</li>
                <li>Datum: {{ record.date }}</li>
                <li>Lesuur: {{ record.time }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import {useForm} from "@inertiajs/inertia-vue3";
import {debounce} from "lodash";
import moment from "moment/moment";

export default {
    layout: AdminLayout,
    props: {
        lessons: Object,
        newCreated: {
            required: false,
            type: Object
        }
    },
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
    computed: {
        yesterday: () => {
            return moment().format('YYYY-MM-DD');
        }
    },
    methods: {
        createLessons() {
            this.lessonForm.post(route('admin.schedules.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.lessonForm.reset();
                },
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

<template>
    <h2 class="mb-3">Rooster uren aanmaken</h2>
    <form class="row g-3" @submit.prevent="createLessons()">
        <div class="col-md-12">
            <label for="multiselectClasses" class="form-label">Klas(sen)</label>
            <multiselect v-model="lessonForm.classes" id="multiselectClasses" :custom-label="classLabel" track-by="id"
                         placeholder="Type om klassen te zoeken" open-direction="bottom" :options="classes" :multiple="true"
                         :searchable="true" :loading="isLoadingClasses" :internal-search="false" :clear-on-select="false" :close-on-select="false"
                         :options-limit="300" :limit="5" :limit-text="limitTextClasses" :max-height="600" :show-no-results="true"
                         @search-change="asyncFindClasses" aria-describedby="feedbackMultiselectClasses" :class="{'is-invalid': lessonForm.errors.classes}">

                <template v-slot:noResult>Oops! Geen klassen gevonden. Verander je zoekopdracht.</template>
                <template v-slot:noOptions>Oops! Geen klassen gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectClasses" class="invalid-feedback" v-if="lessonForm.errors.classes">
                {{ lessonForm.errors.classes }}
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
                         :clear-on-select="true" :close-on-select="false" :options-limit="300" :show-no-results="true"
                         aria-describedby="feedbackMultiselectLessons" :class="{'is-invalid': lessonForm.errors.lessons}">

                <template v-slot:noResult>Oops! Dit lesuur bestaat niet.</template>
                <template v-slot:noOptions>Oops! Geen lesuren gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectLessons" class="invalid-feedback" v-if="lessonForm.errors.lessons">
                {{ lessonForm.errors.lessons }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="check-permanent" v-model="lessonForm.remember">
                <label class="form-check-label" for="check-permanent">
                    Velden behouden
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-success" type="submit" :disabled="lessonForm.processing">
                <i class="fa-solid fa-plus"></i> Toevoegen
            </button>
        </div>
    </form>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import {debounce} from "lodash";
import moment from "moment/moment";

export default {
    name: "create-lessons",
    props: {
        lessons: Object
    },
    data() {
        return {
            lessonForm: useForm({
                classes: [],
                teacher: [],
                subject: [],
                date: null,
                lessons: [],
                remember: false
            }),
            classes: [],
            isLoadingClasses: false,
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
            this.lessonForm.post(route('admin.lessons.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    // Only reset fields if keepValues is false
                    this.toast('success', 'Succesvol toegevoegd!', 'In totaal '+(this.lessonForm.classes.length * this.lessonForm.lessons.length)+' uren toegevoegd!');
                    if (!this.lessonForm.remember) {
                        this.lessonForm.reset();
                    }
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het toevoegen van de uren.')
                }
            })
        },
        classLabel(schoolClass) {
            return `${schoolClass.name}`
        },
        limitTextClasses(count) {
            return `En ${count} andere klas${(count === 1 ? '' : 'sen')}`
        },
        asyncFindClasses: debounce(function (query) {
            this.isLoadingClasses = true
            axios.get(this.route('admin.lessons.getSchoolClasses', {
                query: query
            }))
                .then((response) => {
                    this.classes = response.data
                    this.isLoadingClasses = false
                })
        }, 150),
        teacherLabel(teacher) {
            return `${teacher.abbreviation} - ${teacher.user.firstname} ${teacher.user.lastname}`
        },
        asyncFindTeachers: debounce(function (query) {
            this.isLoadingTeachers = true
            axios.get(this.route('admin.lessons.getTeachers', {
                query: query
            }))
                .then((response) => {
                    this.teachers = response.data
                    this.isLoadingTeachers = false
                })
        }, 150),
        asyncFindSubjects: debounce(function (query) {
            this.isLoadingSubjects = true
            axios.get(this.route('admin.lessons.getSubjects', {
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

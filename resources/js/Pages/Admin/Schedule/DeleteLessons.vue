<template>
    <h2 class="mb-3">Rooster uren uitplannen</h2>
    <form class="row g-3" @submit.prevent="deleteLessons()">
        <div class="col-md-12">
            <label for="multiselectClasses" class="form-label">Klas(en)</label>
            <multiselect v-model="deleteLessenForm.class" id="multiselectClasses" :custom-label="classLabel" track-by="id"
                         placeholder="Type om klassen te zoeken" open-direction="bottom" :options="classes"
                         :searchable="true" :loading="isLoadingClasses" :internal-search="false" :clear-on-select="false"
                         :options-limit="300" :max-height="600" :show-no-results="true" @search-change="asyncFindClasses"
                         aria-describedby="feedbackMultiselectClasses" :class="{'is-invalid': deleteLessenForm.errors.class}">

                <template v-slot:noResult>Oops! Geen klassen gevonden. Verander je zoekopdracht.</template>
                <template v-slot:noOptions>Oops! Geen klassen gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectClasses" class="invalid-feedback" v-if="deleteLessenForm.errors.class">
                {{ deleteLessenForm.errors.class }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="selectDate" class="form-label">Datum</label>
            <input class="form-control" type="date" id="selectDate" v-model="deleteLessenForm.date" :class="{'is-invalid': deleteLessenForm.errors.date}"
                   aria-describedby="feedbackSelectDate" :disabled="!deleteLessenForm.class" @change="getLessons"/>
            <div id="feedbackSelectDate" class="invalid-feedback" v-if="deleteLessenForm.errors.date">
                {{ deleteLessenForm.errors.date }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="multiselectLessons" class="form-label">Lesuren</label>
            <multiselect v-model="deleteLessenForm.lessons" id="multiselectLessons" :custom-label="lessonsLabel" track-by="id"
                         placeholder="Selecteer lesuren" open-direction="bottom" :options="lessons" :multiple="true" :loading="isLoadingLessons"
                         :clear-on-select="true" :close-on-select="false" :options-limit="300" :max-height="600" :show-no-results="true"
                         aria-describedby="feedbackMultiselectLessons" :class="{'is-invalid': deleteLessenForm.errors.lessons}"
                         :disabled="!deleteLessenForm.class || !deleteLessenForm.date">

                <template v-slot:noResult>Oops! Dit lesuur bestaat niet.</template>
                <template v-slot:noOptions>Oops! Geen lesuren gevonden.</template>
            </multiselect>
            <div id="feedbackMultiselectLessons" class="invalid-feedback" v-if="deleteLessenForm.errors.lessons">
                {{ deleteLessenForm.errors.lessons }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="check-permanent" v-model="deleteLessenForm.permanent">
                <label class="form-check-label" for="check-permanent">
                    Permanent verwijderen
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-danger" type="submit" :disabled="deleteLessenForm.processing">
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
            deleteLessenForm: useForm({
                class: null,
                date: null,
                lessons: [],
                permanent: false
            }),
            classes: [],
            isLoadingClasses: false,
            lessons: [],
            isLoadingLessons: false
        }
    },
    methods: {
        deleteLessons() {
            this.deleteLessenForm.post(route('admin.lessons.destroyMultiple'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.deleteLessenForm.reset();
                    this.toast('success', 'Succesvol verwijderd!');
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het verwijderen van deze uren. Probeer het later opnieuw.')
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
        lessonsLabel(lesson) {
            return `${lesson.time}ᵉ uur - ${lesson.subject.name}`
        },
        getLessons() {
            if (this.deleteLessenForm.class && this.deleteLessenForm.date) {
                this.isLoadingLessons = true
                axios.get(this.route('admin.lessons.getLessonsByClass', {
                    class: this.deleteLessenForm.class.id,
                    date: this.deleteLessenForm.date
                }))
                    .then((response) => {
                        this.lessons = response.data
                        this.isLoadingLessons = false
                    })
            }
        }
    },
}
</script>

<template>
    <multiselect v-model="selectedClass" id="multiselectClasses" label="name" track-by="id" class="me-3"
                 placeholder="Klas" open-direction="bottom" :options="classes" :searchable="true"
                 :loading="isLoadingClasses" :internal-search="false" :clear-on-select="false"
                 :options-limit="300" :show-no-results="true" @search-change="asyncFindClasses"
                 @select="selectClass">

        <template v-slot:noResult>Oops! Geen klassen gevonden.</template>
        <template v-slot:noOptions>Oops! Geen klassen gevonden.</template>
    </multiselect>

    <multiselect v-model="selectedTeacher" id="multiselectTeacher" label="abbreviation" track-by="id"
                 placeholder="Docent" open-direction="bottom" :options="teachers" :searchable="true"
                 :loading="isLoadingTeachers" :internal-search="false" :clear-on-select="false"
                 :options-limit="300" :show-no-results="true" @search-change="asyncFindTeachers"
                 @select="selectTeacher">

        <template v-slot:noResult>Oops! Geen docenten gevonden.</template>
        <template v-slot:noOptions>Oops! Geen docenten gevonden.</template>
    </multiselect>
</template>
<script>
import {debounce} from "lodash";

export default {
    data() {
        return {
            selectedClass: null,
            classes: [],
            isLoadingClasses: false,
            selectedTeacher: null,
            teachers: [],
            isLoadingTeachers: false
        }
    },
    methods: {
        asyncFindClasses: debounce(function (query) {
            this.isLoadingClasses = true
            axios.get(this.route('schedule.school-classes', {
                query: query
            }))
                .then((response) => {
                    this.classes = response.data;
                })
                .finally(() => {
                    this.isLoadingClasses = false;
                })
        }, 150),
        selectClass(selectedClass) {
            // Unset teacher param
            this.$parent.params.teacher = null;
            this.selectedTeacher = null;

            this.$parent.params.class = selectedClass.id;
            this.$parent.refetch();
        },
        asyncFindTeachers: debounce(function (query) {
            this.isLoadingTeachers = true
            axios.get(this.route('schedule.teachers', {
                query: query
            }))
                .then((response) => {
                    this.teachers = response.data;
                })
                .finally(() => {
                    this.isLoadingTeachers = false;
                })
        }, 150),
        selectTeacher(selectedTeacher) {
            // Unset class param
            this.$parent.params.class = null;
            this.selectedClass = null;

            this.$parent.params.teacher = selectedTeacher.id;
            this.$parent.refetch();
        }
    },
    mounted() {
        this.asyncFindClasses();
        this.asyncFindTeachers();
    }
}
</script>

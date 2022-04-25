<template>
    <div v-if="subjects.length > 0">
        <ul class="nav nav-tabs">
            <li class="nav-item" v-for="subject in subjects" @click="changeSubject(subject)">
                <a class="nav-link" :class="{'active': params.subject === subject.name}">{{ subject.name }}</a>
            </li>
        </ul>
        <table class="table bg-white table-bordered">
            <thead>
            <tr>
                <th style="width: 10px;">Leerling-nummer</th>
                <th style="width: 10%;" class="border-0 border-end border-2 border-dark">Naam</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(student, index) in schoolClass.students" :key="'classStudent'+index" v-if="schoolClass && schoolClass.students && schoolClass.students.length > 0">
                <td>{{ student.id }}</td>
                <td class="border-0 border-end border-2 border-dark">{{ student.user.firstname }} {{ student.user.lastname }}</td>
                <td v-for="grade in grades[1][student.id]" class="text-center">
                    {{ grade[0].number }} ({{ grade[0].name }})
                </td>
            </tr>
            <tr v-else>
                <td colspan="3">Geen studenten gevonden!</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else>
        <p>Geen vakken gevonden!</p>
    </div>


    {{ schoolClass }}
</template>

<script>
import TeacherLayout from "@/Layouts/TeacherLayout";
import {pickBy} from "lodash";

export default {
    layout: TeacherLayout,
    props: {
        subjects: Object,
        schoolClass: Object,
        grades: Object
    },
    data() {
        return {
            params: {
                subject: null
            }
        }
    },
    mounted() {
        if (this.subjects.length > 0 && !this.params.subject) {
            this.params.subject = this.subjects[0].name; // TODO: Dit meegeven via oldParams in controller
        }
    },
    methods: {
        changeSubject(subject) {
            this.params.subject = subject.name;

            let params = pickBy(this.params);
            this.$inertia.get(this.route('teacher.grades.show', this.schoolClass.id), params, {
                replace: true,
                preserveState: true,
                preserveScroll: true
            });
        }
    }
}
</script>

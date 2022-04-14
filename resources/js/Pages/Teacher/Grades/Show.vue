<template>
    <div v-if="subjects.length > 0">
        <ul class="nav nav-tabs">
            <li class="nav-item" v-for="subject in subjects" @click="changeSubject(subject.name)">
                <a class="nav-link" href="#" :class="{'active': subject.name === params.subject}">{{ subject.name }}</a>
            </li>
        </ul>
        <table class="table bg-white table-bordered" id="teacherGradeTable">
            <thead>
            <tr>
                <th style="width: 10px;">Leerling-nummer</th>
                <th style="width: 10%;" class="border-0 border-end border-2 border-dark">Naam</th>
                <th v-for="name in names" class="name" v-if="names.length > 0">{{ name.name }}</th>
                <th><button class="btn btn-sm btn-success"><i class="fa-solid fa-plus"></i> Toets toevoegen</button></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(student, index) in schoolClass.students" :key="'classStudent'+index" v-if="schoolClass && schoolClass.students && schoolClass.students.length > 0">
                <td>{{ student.id }}</td>
                <td class="border-0 border-end border-2 border-dark">{{ student.user.firstname }} {{ student.user.lastname }}</td>
                <template v-for="name in names" v-if="names.length > 0">
                    <td v-for="grade in grades[name.id][student.id]" v-if="grades[name.id] && grades[name.id][student.id]" class="grade">
                        <input type="number" :value="grade.number" min="1" max="10" step="0.1">
                    </td>
                    <td v-else class="grade">
                        <input type="number" min="1" max="10" step="0.1">
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
    <div v-else>
        <p>Geen vakken gevonden!</p>
    </div>
</template>
<script>
import {pickBy} from "lodash";
import TeacherLayout from "@/Layouts/TeacherLayout";

export default {
    layout: TeacherLayout,
    props: {
        subjects: Object,
        subject: String,
        schoolClass: Object,
        grades: Object
    },
    data() {
        return {
            params: {
                subject: this.subject
            },
            names: []
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
        grades: {
            handler() {
                this.getGradeNames();
            }
        }
    },
    mounted() {
        this.getGradeNames();
    },
    methods: {
        changeSubject(subject) {
            this.params.subject = subject;
        },
        getGradeNames() {
            this.names = [];
            if (this.grades && Object.keys(this.grades).length > 0) {
                const keys = Object.keys(this.grades);

                keys.forEach((val) => {
                    this.names.push(Object.values(this.grades[val])[0][0]['assignment']);
                })
            }
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

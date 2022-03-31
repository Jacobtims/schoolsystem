<template>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center">
                <h2 class="mb-1">{{ lesson.school_class.name }} - {{ lesson.time }}e lesuur</h2>
<!--                <p class="mb-0">9/10 aanwezig</p>-->
            </div>
            <hr/>
            <div class="table-responsive">
                <div class="d-flex justify-content-between">
                    <Link :href="route('teacher.attendance')" as="button" type="button" class="btn btn-secondary"><i class="fa-solid fa-angles-left"></i> Terug</Link>
                    <button class="btn btn-success" @click="saveAttendance"><i class="fa-solid fa-check"></i> Opslaan</button>
                </div>
                <table class="table table-borderless my-2" id="table-attendances">
                    <thead>
                    <tr>
                        <th style="width: 50px"></th>
                        <th style="width: 100px;">Leerling-<wbr>nummer</th>
                        <th>Voornaam</th>
                        <th>Achternaam</th>
                        <th class="text-center">Aanwezig</th>
                        <th class="text-center">Afwezig</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(student, index) in lesson.school_class.students" :key="'student'+index" v-if="lesson.school_class.students.length > 0">
                        <td>
                            <img :src="'https://eu.ui-avatars.com/api/?size=50&name='+student.user.firstname+'+'+student.user.lastname" alt="Profile picture"
                                 class="rounded-circle" width="50" height="50"/>
                        </td>
                        <td>{{ student.id }}</td>
                        <td>{{ student.user.firstname }}</td>
                        <td>{{ student.user.lastname }}</td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" v-model="present[student.id]"
                                   @change="change(student.id, 'present')" :disabled="disabled[student.id]">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" v-model="absent[student.id]"
                                   @change="change(student.id, 'absent')">
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="9">Er zitten geen studenten in deze klas!</td>
                    </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between">
                    <Link :href="route('teacher.attendance')" as="button" type="button" class="btn btn-secondary"><i class="fa-solid fa-angles-left"></i> Terug</Link>
                    <button class="btn btn-success" @click="saveAttendance"><i class="fa-solid fa-check"></i> Opslaan</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TeacherLayout from "@/Layouts/TeacherLayout";
import { Link } from '@inertiajs/inertia-vue3'
import {Inertia} from "@inertiajs/inertia";

export default {
    layout: TeacherLayout,
    components: {
        Link
    },
    props: {
        lesson: Object
    },
    data() {
        return {
            present: {},
            absent: {},
            disabled: {}
        }
    },
    mounted() {
        this.fillAbsent();
        this.fillPresent();
    },
    methods: {
        fillAbsent() {
            this.lesson.absentees.forEach((attendance) => {
                if(attendance.present === 0) {
                    this.absent[attendance.student_id] = true;
                    this.disabled[attendance.student_id] = true;
                }
            });
        },
        fillPresent() {
            this.lesson.school_class.students.forEach((student) => {
                if (!this.absent[student.id]) {
                    this.present[student.id] = true;
                }
            });
        },
        change(studentId, method) {
            if (!this.disabled[studentId]) {
                if (!this.absent[studentId] && !this.present[studentId]) {
                    this.present[studentId] = true;
                }
                if (this.absent[studentId] && this.present[studentId] && method === 'absent') {
                    this.present[studentId] = false;
                }
                if (this.absent[studentId] && this.present[studentId] && method === 'present') {
                    this.absent[studentId] = false;
                }
            } else {
                this.present[studentId] = false;
                this.absent[studentId] = true;
            }
        },
        saveAttendance() {
            Inertia.post(route('teacher.attendance.store'), {
                lessonId: this.lesson.id,
                present: this.present,
                absent: this.absent
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.toast('success', 'Succesvol opgeslagen!')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het opslaan. Probeer het opnieuw.')
                }
            })
        }
    }
}
</script>
<style lang="scss">
#table-attendances {
    tbody tr td {
        vertical-align: middle;
    }
}
</style>

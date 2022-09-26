<template>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center">
                <h4 class="mb-1">{{ lesson.subject.name }} - {{ lesson.time }}e lesuur</h4>
                <div class="d-flex w-100 justify-content-evenly my-1">
                    <h6 class="mb-0">Klas {{ lesson.school_class.name }}</h6>
                    <h6 class="mb-0 text-success">{{ present }}/{{ students.length }} aanwezig</h6>
                    <h6 class="mb-0 text-danger">{{ Object.keys(absences).length }} afwezig</h6>
                </div>
            </div>
            <hr/>
            <!-- All students -->
            <div class="table-responsive">
                <div class="d-flex justify-content-between">
                    <Link :href="route('teacher.attendance')" as="button" type="button" class="btn btn-secondary">
                        <i class="fa-solid fa-angles-left"></i> Terug
                    </Link>
                    <button class="btn btn-success" @click="saveAttendance" :disabled="attendanceForm.processing">
                        <i class="fa-solid fa-check"></i> Opslaan
                    </button>
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
                    <tr v-for="(student, index) in students" :key="'student'+index" v-if="students.length > 0">
                        <td>
                            <img :src="student.user.profile_photo_url" alt="Profile picture"
                                 class="rounded-circle" width="50" height="50"/>
                        </td>
                        <td>{{ student.id }}</td>
                        <td>{{ student.user.firstname }}</td>
                        <td>{{ student.user.lastname }}</td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-as-radio" type="radio" :name="'present' + student.id"
                                   v-model="attendanceForm.present[student.id]" :value="true">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-as-radio" type="radio" :name="'present' + student.id"
                                   v-model="attendanceForm.present[student.id]" :value="false">
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="9">Geen studenten gevonden!</td>
                    </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between">
                    <Link :href="route('teacher.attendance')" as="button" type="button" class="btn btn-secondary">
                        <i class="fa-solid fa-angles-left"></i> Terug
                    </Link>
                    <button class="btn btn-success" @click="saveAttendance" :disabled="attendanceForm.processing">
                        <i class="fa-solid fa-check"></i> Opslaan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TeacherLayout from "@/Layouts/TeacherLayout.vue";
import {Link, useForm} from '@inertiajs/inertia-vue3'

export default {
    layout: TeacherLayout,
    components: {
        Link
    },
    props: {
        lesson: Object,
        students: Object,
        absences: Object
    },
    data() {
        return {
            attendanceForm: useForm({
                present: {}
            }),
            present: (this.students.length - Object.keys(this.absences).length)
        }
    },
    mounted() {
        this.fillPresent();
        this.$inertia.on('before', (event) => {
            if (this.attendanceForm.isDirty && (event.detail.visit.url != route('teacher.attendance.store', this.lesson.id))) {
                alert('Je wijzigingen zijn nog niet opgeslagen!');
                event.preventDefault();
            }
        });
    },
    methods: {
        fillPresent() {
            // Set present status
            let present = {};
            this.students.forEach(student => {
                if (this.absences[student.id] && this.absences[student.id].student_id === student.id) {
                    present[student.id] = false;
                } else {
                    present[student.id] = true;
                }
            });
            // Update present without affecting .isDirty
            this.attendanceForm.defaults({
                present: present,
            }).reset();
        },
        saveAttendance() {
            this.attendanceForm.post(route('teacher.attendance.store', this.lesson.id), {
                onSuccess: () => {
                    this.toast('success', 'Succesvol opgeslagen!')
                },
                onError: () => {
                    this.toast('error', 'Fout!', 'Er is iets fout gegegaan tijdens het opslaan. Probeer het opnieuw.')
                }
            });
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

.checkbox-as-radio {
    border-radius: 0.25em !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e") !important;
}
</style>

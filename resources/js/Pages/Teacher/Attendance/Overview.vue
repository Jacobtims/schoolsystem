<template>
    <div class="card">
        <div class="card-body">
            <table class="table table-borderless table-hover" id="table-lessons-attendance" v-if="lessons.length > 0">
                <thead>
                <tr>
                    <th>Les uur</th>
                    <th>Datum</th>
                    <th>Vak</th>
                    <th>Klas</th>
                    <th>Registratie</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(lesson, index) in lessons" :key="'lesson'+index" class="clickable">
                    <td>{{ lesson.time }}e</td>
                    <td>{{ $moment(lesson.date).format('LL') }}</td>
                    <td>{{ lesson.subject.name }}</td>
                    <td>{{ lesson.school_class.name }}</td>
                    <td v-if="!lesson.attendance_registered" class="text-danger">Niet geregistreerd</td>
                    <td v-else class="text-success">Geregistreerd</td>
                    <td v-if="!lesson.attendance_registered">
                        <Link :href="route('teacher.attendance.show', lesson.id)" as="button" type="button" class="btn btn-sm btn-success">Registreer</Link>
                    </td>
                    <td v-else>
                        <Link :href="route('teacher.attendance.show', lesson.id)" as="button" type="button" class="btn btn-sm btn-secondary">Wijzigen</Link>
                    </td>
                </tr>
                </tbody>
            </table>
            <h6 v-else>Je hebt geen lessen vandaag!</h6>
        </div>
    </div>
</template>

<script>
import TeacherLayout from "@/Layouts/TeacherLayout.vue";
import { Link } from '@inertiajs/inertia-vue3'

export default {
    layout: TeacherLayout,
    components: {
        Link
    },
    props: {
        lessons: Object
    }
}
</script>
<style lang="scss">
#table-lessons-attendance {
    font-size: 18px;
}
</style>

<template>
    <div class="card">
        <div class="card-body">
            <table class="table table-borderless table-hover" id="table-classes" v-if="classes.length > 0">
                <thead>
                <tr>
                    <th>Klas</th>
                    <th>Aantal leerlingen</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(schoolClass, index) in classes" :key="'class'+index" class="clickable">
                    <td>{{ schoolClass.name }}</td>
                    <td>{{ schoolClass.students_count }}</td>
                    <td>
                        <Link :href="route('teacher.grades.show', schoolClass.name)" as="button" type="button" class="btn btn-sm btn-primary">Selecteer</Link>
                    </td>
                </tr>
                </tbody>
            </table>
            <h6 v-else>Er zijn geen klassen gevonden!</h6>
        </div>
    </div>
</template>

<script>
import TeacherLayout from "@/Layouts/TeacherLayout";
import { Link } from '@inertiajs/inertia-vue3'

export default {
    layout: TeacherLayout,
    components: {
        Link
    },
    props: {
        classes: Object
    }
}
</script>
<style lang="scss">
#table-classes {
    font-size: 18px;
}
</style>

<template>
    <div class="row">
        <div class="col-md-3 mb-3">
            <h3>Browsersessies</h3>
            <p>Beheer en log uit op actieve sessies op andere browsers en apparaten.</p>
        </div>
        <div class="col-md-9 mb-3">
            <Sessions :sessions="sessions"/>
        </div>
    </div>


</template>
<script>
import Sessions from "@/Pages/User/Components/Sessions.vue";
import StudentLayout from "@/Layouts/StudentLayout.vue";
import TeacherLayout from "@/Layouts/TeacherLayout.vue";

export default {
    components: {Sessions},
    props: {
        sessions: Array,
        role: String
    },
    layout: (h, page) => {
        if (page.props.role === 'Student') {
            return h(StudentLayout, () => [page]);
        }
        else if (page.props.role === 'Teacher') {
            return h(TeacherLayout, () => [page]);
        }
    },
}
</script>

<template>
    <div class="row">
        <!-- Profile information -->
        <div class="col-md-3 mb-3">
            <h3>Profielgegevens</h3>
            <p>Bewerk de profielgegevens van je account.</p>
        </div>
        <div class="col-md-9">
            <UserProfile :user="user"/>
        </div>

        <div class="col-md-12 my-5"><hr/></div>

        <!-- Browsersessies -->
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
import UserProfile from "@/Pages/User/Components/UserProfile.vue";

export default {
    components: {UserProfile, Sessions},
    props: {
        sessions: Array,
        role: String,
        user: Object
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

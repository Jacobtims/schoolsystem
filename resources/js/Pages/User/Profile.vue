<template>
    <div class="row">
        <!-- Profile information -->
        <div class="col-md-3 mb-3">
            <h3>Profielgegevens</h3>
            <p>Bewerk de profielgegevens van je account.</p>
        </div>
        <div class="col-md-9 mb-3">
            <UserProfile :user="user"/>
        </div>

        <div class="col-md-12 my-5"><hr/></div>

        <!-- Update password -->
        <div class="col-md-3 mb-3">
            <h3>Wachtwoord aanpassen</h3>
            <p>Zorg dat je een lang en willekeurig wachtwoord gebruikt om veilig te blijven.</p>
        </div>
        <div class="col-md-9 mb-3">
            <UpdatePassword/>
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
import UpdatePassword from "@/Pages/User/Components/UpdatePassword.vue";

export default {
    components: {UpdatePassword, UserProfile, Sessions},
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

<template>
    <div class="row">
        <div class="col-md-6 border-end">
            <create-lessons :lessons="lessons"></create-lessons>
        </div>
        <div class="col-md-6">
            <delete-lessons></delete-lessons>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import CreateLessons from "@/Pages/Admin/Schedule/CreateLessons.vue";
import DeleteLessons from "@/Pages/Admin/Schedule/DeleteLessons.vue";
export default {
    layout: AdminLayout,
    components: {DeleteLessons, CreateLessons},
    props: {
        lessons: Object
    }
}
</script>

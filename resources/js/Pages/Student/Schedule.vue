<template>
    <Schedule/>
</template>
<script>
import Schedule from "@/Components/Schedule/Schedule.vue";
import StudentLayout from "@/Layouts/StudentLayout.vue";

export default {
    layout: StudentLayout,
    components: {Schedule}
}
</script>

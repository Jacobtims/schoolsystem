<template>
    <student-layout>
        <div class="row">
            <div class="col-2">
                <div>-</div>
                <div v-for="(hour, index) in standardLessons" :key="'hour'+index">
                    <h5>{{ hour.from }} - {{ hour.to }}</h5>
                </div>
            </div>
            <div class="col-2" v-for="(day, index) in dates" :key="index">
                <h5>{{ day }}</h5>

                <div v-for="hour in standardLessons">
                    <div v-if="lessons[day] && lessons[day][hour.id]">
                        <div v-for="les in lessons[day][hour.id]">
                            {{ les.subject.name }}
                        </div>
                    </div>
                    <div v-else>
                        &nbsp;
                    </div>
                </div>
            </div>

        </div>

        <!-- Loop through dates -> loop through standard hours -> get hours -->
    </student-layout>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout";

export default {
    props: {
        lessons: Object,
        standardLessons: Object,
        dates: Array
    },
    components: {
        StudentLayout
    },
}
</script>
<style lang="scss">

</style>

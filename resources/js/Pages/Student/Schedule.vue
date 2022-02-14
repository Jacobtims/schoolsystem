<template>
    <student-layout>
        <div class="row">
            <div class="col-2">
                 <h5 v-for="(lesson, index) in standardLessons">{{ lesson.from }} - {{ lesson.to }}</h5>
            </div>
            <div class="col-2" v-for="(day) in lessons">
                <div class="schedule-lesson" v-for="(lesson, index) in day" :key="'lesson'+index">
                    {{ lesson }}
                </div>
            </div>

        </div>

<!--        <table class="table table-borderless">-->
<!--            <tr>-->
<!--                <th>Time</th>-->
<!--                <th>1</th>-->
<!--                <th>2</th>-->
<!--                <th>3</th>-->
<!--                <th>4</th>-->
<!--                <th>5</th>-->
<!--            </tr>-->
<!--            <tr v-for="(day, index) in standardLessons" :key="'row'+index">-->
<!--                <td>-->
<!--                    {{ day.from }} - {{ day.to }}-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->

        <div v-for="day in lessons">
            {{ day }}<br/><br/>
        </div>
    </student-layout>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout";

export default {
    props: {
        lessons: Object,
        standardLessons: Object
    },
    components: {
        StudentLayout
    },
}
</script>
<style lang="scss">

</style>

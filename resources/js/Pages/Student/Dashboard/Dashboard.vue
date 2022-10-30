<template>
    <h1 class="text-4xl font-bold text-dark-blue">{{ greeting }}, {{ firstname }}!</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <Card class="">
            <h3 class="text-2xl font-semibold text-dark-blue mb-5">Vandaag</h3>
            <SmallSchedule :events="events"/>
        </Card>
        <Card class="">
            <h3 class="text-2xl font-semibold text-dark-blue mb-5">Laatst toegevoegd</h3>
            <Grades :grades="grades"/>
        </Card>
        <Card class="">
            <h3 class="text-2xl font-semibold text-dark-blue mb-5">Vakanties</h3>
        </Card>
    </div>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout.vue";
import Card from '@/Components/Card.vue';
import SmallSchedule from "@/Pages/Student/Dashboard/SmallSchedule.vue";
import Grades from "@/Pages/Student/Dashboard/Grades.vue";

export default {
    layout: StudentLayout,
    props: {
        events: Object,
        grades: Object
    },
    components: {
        Grades,
        SmallSchedule,
        Card
    },
    computed: {
        greeting() {
            const now = new Date();
            const hours = now.getHours();

            if (hours < 12) {
                return 'Goedemorgen';
            } else if (hours >= 12 && hours <= 17) {
                return 'Goedemiddag';
            } else if (hours >= 17 && hours <= 24) {
                return 'Goedeavond';
            }
        },
        firstname() {
            return this.$page.props.auth.user.firstname;
        }
    }
}
</script>

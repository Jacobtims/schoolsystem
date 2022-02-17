<template>
    <student-layout>
        <div class="card mb-3" id="schedule-navigator">
            <div class="card-body d-flex justify-content-center">
                <section>
                    <button class="navigator"><i class="fa-solid fa-arrow-left"></i></button>
                    <span class="date mx-4">{{ this.$moment(dates[0]).format('LL') }} - {{ this.$moment(dates[dates.length - 1]).format('LL') }}</span>
                    <button class="navigator"><i class="fa-solid fa-arrow-right"></i></button>
                </section>
            </div>
        </div>

        <!-- https://codepen.io/JacobTimmer/pen/eYeGwVw -->
        <!-- https://drive.google.com/file/d/1BLSbjaFuFEkEBap1taeQBfYL5Tgsp8J2/view?usp=sharing -->
        <!-- https://www.geeksforgeeks.org/how-to-convert-jquery-to-javascript/ -->
        <!-- https://drive.google.com/file/d/1I-Ckq3yBQ4X7f4VuQO3LlhO3nR9DYHWX/view?usp=sharing -->

        <div class="card">
            <div class="row">
                <div class="col-2">
                    <div>-</div>
                    <div v-for="(hour, index) in standardLessons" :key="'hour'+index">
                        <h5>{{ hour.from }} - {{ hour.to }}</h5>
                    </div>
                </div>
                <div class="col-2" v-for="(day, index) in dates" :key="index">
                    <h5>{{ this.$moment(day).format('L') }}</h5>

                    <div v-for="hour in standardLessons">
                        <div v-if="lessons[this.$moment(day).format('YYYY-MM-DD')] && lessons[this.$moment(day).format('YYYY-MM-DD')][hour.id]">
                            <span v-for="les in lessons[this.$moment(day).format('YYYY-MM-DD')][hour.id]">
                                {{ les.subject.name }},
                            </span>
                        </div>
                        <div v-else>
                            &nbsp;
                        </div>
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
#schedule-navigator {
    .date {
        font-size: 18px;
    }
    .navigator {
        border: 0;
        background: none;
        font-size: 20px;
    }
}
</style>

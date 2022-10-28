<template>
    <div class="flex flex-col h-full">
        <div class="grid grid-flow-row lg:grid-flow-col gap-4 mb-4 items-center">
            <div class="flex gap-3 flex-col md:flex-row">
                <SelectBoxes/>
            </div>
            <div class="flex items-center justify-between lg:justify-center">
                <Button btn="secondary" class="lg:hidden" @click="$refs.calendar.prev()">
                    <i class="fa-solid fa-arrow-left" title="Vorige"></i>
                </Button>
                <div class="text-lg font-semibold text-center">
                    {{ $dayjs(params.start).format('LL') }}
                    <span class="hidden lg:inline">- {{ $dayjs(params.end).subtract(1, 'day').format('LL') }}</span>
                </div>
                <Button btn="secondary" class="lg:hidden" @click="$refs.calendar.next()">
                    <i class="fa-solid fa-arrow-right" title="Volgende"></i>
                </Button>
            </div>
            <div class="hidden lg:flex justify-end gap-3">
                <Button btn="secondary" @click="$refs.calendar.prev()">
                    <i class="fa-solid fa-arrow-left" title="Vorige"></i>
                </Button>
                <Button btn="secondary" @click="$refs.calendar.next()">
                    <i class="fa-solid fa-arrow-right" title="Volgende"></i>
                </Button>
            </div>
        </div>

        <Calendar ref="calendar" :get-events="getEvents"/>
    </div>

    <!-- Lesson modal -->
    <lesson-modal :open-modal="openModal" :lesson-id="lessonId"/>
</template>

<script>
import LessonModal from "@/Pages/Components/LessonModal.vue";
import Calendar from "@/Components/Schedule/Calendar.vue";
import SelectBoxes from '@/Components/Schedule/SelectBoxes.vue';
import Card from "@/Components/Card.vue";
import Button from "@/Components/Button.vue";

export default {
    props: {
        lessons: Object,
    },
    components: {
        LessonModal,
        Calendar,
        SelectBoxes,
        Card,
        Button
    },
    data: function () {
        return {
            params: {
                start: "",
                end: "",
                class: null,
                teacher: null
            },
            openModal: false,
            lessonId: null
        }
    },
    methods: {
        refetch() {
            this.$refs.calendar.refetch();
        },
        getEvents(info, successCallback, failureCallback) {
            this.params.start = info.start;
            this.params.end = info.end;

            axios.get(this.route('schedule.lessons'), {
                params: {
                    start: this.$dayjs(info.start).format('YYYY-MM-DD'),
                    end: this.$dayjs(info.end).format('YYYY-MM-DD'),
                    class: this.params.class,
                    teacher: this.params.teacher
                }
            })
                .then((response) => {
                    successCallback(response.data);
                })
                .catch(() => {
                    this.toast('error', 'Er is iets fout gegaan bij het ophalen van de uren!');
                    failureCallback("Er is iets fout gegaan bij het ophalen van de uren!");
                })
        },
    }
}
</script>

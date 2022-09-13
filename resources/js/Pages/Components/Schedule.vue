<template>
    <div id="scheduleWrapper">
        <div class="card mb-3" id="scheduleNavigator">
            <div class="card-body">
                <div class="d-flex mb-2">
                    <multiselect v-model="selectedClass" id="multiselectClasses" label="name" track-by="id" class="me-3"
                                 placeholder="Klas" open-direction="bottom" :options="classes" :searchable="true"
                                 :loading="isLoadingClasses" :internal-search="false" :clear-on-select="false"
                                 :options-limit="300" :show-no-results="true" @search-change="asyncFindClasses"
                                 @select="selectClass">

                        <template v-slot:noResult>Oops! Geen klassen gevonden. Verander je zoekopdracht.</template>
                        <template v-slot:noOptions>Oops! Geen klassen gevonden.</template>
                    </multiselect>
                    <multiselect v-model="selectedTeacher" id="multiselectTeacher" label="abbreviation" track-by="id"
                                 placeholder="Docent" open-direction="bottom" :options="teachers" :searchable="true"
                                 :loading="isLoadingTeachers" :internal-search="false" :clear-on-select="false"
                                 :options-limit="300" :show-no-results="true" @search-change="asyncFindTeachers"
                                 @select="selectTeacher">

                        <template v-slot:noResult>Oops! Geen docenten gevonden. Verander je zoekopdracht.</template>
                        <template v-slot:noOptions>Oops! Geen docenten gevonden.</template>
                    </multiselect>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="navigator" @click="prev">
                        <i class="fa-solid fa-arrow-left" title="<"></i>
                    </button>
                    <span class="date mx-4">
                    {{ $dayjs(params.start).format('LL') }} - {{ $dayjs(params.end).subtract(1, 'day').format('LL') }}
                </span>
                    <button class="navigator" @click="next">
                        <i class="fa-solid fa-arrow-right" title=">"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card border-0" id="scheduleCalendar">
            <FullCalendar
                ref="schedule"
                :options='calendarOptions'
            >
                <template v-slot:eventContent='arg'>
                    <p class="lesson-subject">{{ arg.event.extendedProps.subject.name }}</p>
                    <p class="lesson-teacher">{{ arg.event.extendedProps.teacher.abbreviation }}</p>
                    <p class="lesson-classroom">{{ arg.event.extendedProps.classroom.name }}</p>
                </template>
            </FullCalendar>
        </div>
    </div>

    <!-- Lesson modal -->
    <lesson-modal :open-modal="openModal" :lesson-id="lessonId"/>
</template>

<script>
import '@fullcalendar/core/vdom' // solves problem with Vite
import FullCalendar from '@fullcalendar/vue3';
import timeGridPlugin from '@fullcalendar/timegrid';
import nlLocal from '@fullcalendar/core/locales/nl';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import {debounce} from "lodash";
import LessonModal from "@/Pages/Components/LessonModal.vue";

export default {
    props: {
        lessons: Object,
    },
    components: {
        LessonModal,
        FullCalendar
    },
    data: function () {
        return {
            calendarOptions: {
                locale: nlLocal,
                plugins: [
                    timeGridPlugin,
                    bootstrap5Plugin
                ],
                themeSystem: 'bootstrap5',
                headerToolbar: null,
                dayHeaderFormat: {
                    weekday: 'short',
                    month: 'long',
                    day: 'numeric',
                    omitCommas: true
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: 'short'
                },
                nowIndicator: true,
                initialView: 'timeGridWeek',
                events: this.getEvents,
                dayMaxEvents: true,
                weekends: false,
                allDaySlot: false,
                eventClick: this.openLessonModal,
                slotDuration: '00:15:00',
                eventColor: '#4782f6'
            },
            params: {
                start: "",
                end: "",
                class: null,
                teacher: null
            },
            openModal: false,
            lessonId: null,

            selectedClass: null,
            classes: [],
            isLoadingClasses: false,
            selectedTeacher: null,
            teachers: [],
            isLoadingTeachers: false
        }
    },
    methods: {
        next() {
            this.$refs.schedule.getApi().next();
        },
        prev() {
            this.$refs.schedule.getApi().prev()
        },
        refetch() {
            this.$refs.schedule.getApi().refetchEvents();
        },
        getEvents(info, successCallback, failureCallback) {
            this.params.start = info.start;
            this.params.end = info.end;

            axios.get(this.route('student.schedules.get-lessons'), {
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
        openLessonModal(info) {
            this.openModal = true;
            this.lessonId = info.event.id;
        },
        asyncFindClasses: debounce(function (query) {
            this.isLoadingClasses = true
            axios.get(this.route('student.schedules.getSchoolClasses', {
                query: query
            }))
                .then((response) => {
                    this.classes = response.data;
                })
                .finally(() => {
                    this.isLoadingClasses = false;
                })
        }, 150),
        selectClass(selectedClass) {
            // Unset teacher param
            this.params.teacher = null;
            this.selectedTeacher = null;

            this.params.class = selectedClass.id;
            this.refetch();
        },
        asyncFindTeachers: debounce(function (query) {
            this.isLoadingTeachers = true
            axios.get(this.route('student.schedules.getTeachers', {
                query: query
            }))
                .then((response) => {
                    this.teachers = response.data;
                })
                .finally(() => {
                    this.isLoadingTeachers = false;
                })
        }, 150),
        selectTeacher(selectedTeacher) {
            // Unset class param
            this.params.class = null;
            this.selectedClass = null;

            this.params.teacher = selectedTeacher.id;
            this.refetch();
        }
    },
    mounted() {
        this.asyncFindClasses();
        this.asyncFindTeachers();
    }
}
</script>
<style lang="scss">
.fc-event-main {
    cursor: pointer;
    margin: 3px;
}

.lesson-subject {
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 1px;
}

.lesson-teacher,
.lesson-classroom {
    font-size: 13px;
    margin-bottom: 1px;
}

#scheduleNavigator {
    .date {
        font-size: 18px;
    }

    .navigator {
        border: 0;
        background: none;
        font-size: 20px;
    }

    .multiselect {
        max-width: 250px;
    }
}

.fc .fc-timegrid-col.fc-day-today {
    background: none;
}
</style>

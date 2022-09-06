<template>
    <div class="card mb-3" id="schedule-navigator">
        <div class="card-body">
            <div class="d-flex mb-2">
                <multiselect v-model="selectedClass" id="multiselectClasses" label="name" track-by="id" class="me-3"
                             placeholder="Klas" open-direction="bottom" :options="classes" :searchable="true"
                             :loading="isLoadingClasses" :internal-search="false" :clear-on-select="false" :options-limit="300"
                             :show-no-results="true" @search-change="asyncFindClasses" @select="selectClass">

                    <template v-slot:noResult>Oops! Geen klassen gevonden. Verander je zoekopdracht.</template>
                    <template v-slot:noOptions>Oops! Geen klassen gevonden.</template>
                </multiselect>
                <multiselect v-model="selectedTeacher" id="multiselectTeacher" label="abbreviation" track-by="id"
                             placeholder="Docent" open-direction="bottom" :options="teachers" :searchable="true"
                             :loading="isLoadingTeachers" :internal-search="false" :clear-on-select="false" :options-limit="300"
                             :show-no-results="true" @search-change="asyncFindTeachers" @select="selectTeacher">

                    <template v-slot:noResult>Oops! Geen docenten gevonden. Verander je zoekopdracht.</template>
                    <template v-slot:noOptions>Oops! Geen docenten gevonden.</template>
                </multiselect>
            </div>
            <div class="d-flex justify-content-center">
                <button class="navigator" @click="previousWeek"><i class="fa-solid fa-arrow-left" title="<"></i>
                </button>
                <span class="date mx-4">{{ $moment(dates[0]).format('LL') }} -
                    {{ $moment(dates[dates.length - 1]).format('LL') }}</span>
                <button class="navigator" @click="nextWeek"><i class="fa-solid fa-arrow-right" title=">"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card card-body py-0 pe-0 w-100" id="schedule-table">
        <div class="cd-schedule js-full">
            <div class="timeline">
                <ul>
                    <li><span>07:00</span></li>
                    <li><span>07:30</span></li>
                    <li><span>08:00</span></li>
                    <li><span>08:30</span></li>
                    <li><span>09:00</span></li>
                    <li><span>09:30</span></li>
                    <li><span>10:00</span></li>
                    <li><span>10:30</span></li>
                    <li><span>11:00</span></li>
                    <li><span>11:30</span></li>
                    <li><span>12:00</span></li>
                    <li><span>12:30</span></li>
                    <li><span>13:00</span></li>
                    <li><span>13:30</span></li>
                    <li><span>14:00</span></li>
                    <li><span>14:30</span></li>
                    <li><span>15:00</span></li>
                    <li><span>15:30</span></li>
                    <li><span>16:00</span></li>
                </ul>
            </div>

            <div class="lessons">
                <ul class="wrap">
                    <li class="lessons-group" v-for="date in dates">
                        <div class="top-info">
                            <span id="day">{{ $moment(date).format('dddd') }}</span>
                            <span>{{ $moment(date).format('DD/MM') }}</span>
                        </div>
                        <ul>
                            <template v-for="lesson in lessons[$moment(date).format('YYYY-MM-DD')]" style="position: relative; z-index: 10;">
                                <li class="single-lesson" v-for="(les, index) in lesson"
                                    :style="{'top': singleLessonTop(les.time.from)+'px', 'height': singleLessonHeight(les.time.to)+'px', 'width': (100 / lesson.length)+'%', 'left': (index * (100 / lesson.length))+'%'}"
                                    :class="{'lesson-deleted': les.deleted}">
                                    <a class="clickable" @click="openLessonModal(les)">
                                        <span class="lesson-name">{{ les.subject.name }}</span>
                                        <span class="lesson-abbreviation">{{ les.teacher.abbreviation }}</span>
                                        <span class="lesson-classroom">{{ les.classroom.name }}</span>
                                        <section class="lesson-icons">
                                            <i class="fa-solid fa-triangle-exclamation fa-2xl text-white" v-if="les.deleted"></i>
                                        </section>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <Dialog v-model:visible="openModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            :header="activeLesson.subject.name" :draggable="false" :modal="true" :dismissableMask="true"
            @hide="closeLessonModal" v-if="activeLesson">
        <table class="table table-borderless" id="table-grades">
            <tr>
                <td><strong>Klas:</strong></td>
                <td>{{ activeLesson.school_class.name }}</td>
            </tr>
            <tr>
                <td><strong>Datum:</strong></td>
                <td>{{ $moment(activeLesson.date).format('LL') }}</td>
            </tr>
            <tr>
                <td><strong>Tijd:</strong></td>
                <td>{{ activeLesson.time.from }} - {{ activeLesson.time.to }}</td>
            </tr>
            <tr>
                <td><strong>Docent:</strong></td>
                <td>{{ activeLesson.teacher.abbreviation }} ({{ activeLesson.teacher.student_name }})</td>
            </tr>
            <tr>
                <td><strong>Lokaal:</strong></td>
                <td>{{ activeLesson.classroom.name }}</td>
            </tr>
        </table>
        <div v-if="!activeLesson.deleted && activeLesson.teacher_id === $attrs.user.teacher.id">
            <hr/>
            <table class="table table-borderless">
                <tr v-for="(hw, index) in activeLesson.homework">
                    <td v-if="hw.type === 'activity'" title="Activiteit" style="width: 30px;"><i class="fa-solid fa-person-running"></i></td>
                    <td v-else-if="hw.type === 'test'" title="Toets" style="width: 30px;"><i class="fa-solid fa-pencil"></i></td>
                    <td v-else title="Huiswerk" style="width: 30px;"><i class="fa-solid fa-book"></i></td>
                    <td style="word-break: break-all; width: 350px;">{{ hw.description }}</td>
                    <td class="text-end">{{ $moment(hw.created_at).format('LLL') }}</td>
                    <td style="width: 10px;"><i class="fa-solid fa-trash text-danger clickable" @click="deleteHomework(hw.id, index)"></i></td>
                </tr>
            </table>
            <button class="btn btn-sm btn-primary" @click="openHomeworkModal"><i class="fa-solid fa-plus"></i> Huiswerk toevoegen</button>
        </div>
    </Dialog>

    <!-- Homework modal -->
    <Dialog v-model:visible="openHwModal" :breakpoints="{'1200px': '50vw', '992px': '65vw'}" :style="{width: '40vw'}"
            header="Huiswerk toevoegen" :draggable="false" :modal="true" :dismissableMask="true"
            @hide="closeHomeworkModal">
        <div>
            <form class="row g-3" @submit.prevent="addHomework">
                <div class="col-md-12">
                    <label for="inputType" class="form-label">Type</label>
                    <select class="form-select" :class="{'is-invalid': homeworkForm.errors.type}" id="inputType"
                            v-model="homeworkForm.type" aria-describedby="feedbackType" required>
                        <option value="homework">Huiswerk</option>
                        <option value="test">Toets</option>
                        <option value="activity">Activiteit</option>
                    </select>

                    <div id="feedbackType" class="invalid-feedback" v-if="homeworkForm.errors.type">
                        {{ homeworkForm.errors.type }}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputDescription" class="form-label">Omschrijving</label>
                    <textarea type="number" class="form-control" :class="{'is-invalid': homeworkForm.errors.description}"
                              id="inputDescription" v-model="homeworkForm.description" aria-describedby="feedbackDescription"
                              required min="1" rows="3"></textarea>
                    <div id="feedbackDescription" class="invalid-feedback" v-if="homeworkForm.errors.description">
                        {{ homeworkForm.errors.description }}
                    </div>
                </div>
            </form>
        </div>
        <template #footer>
            <button class="btn btn-success" type="submit" @click="addHomework" autofocus :disabled="homeworkForm.processing"><i class="fa-solid fa-check"></i> Toevoegen</button>
            <button class="btn btn-outline-secondary" @click="closeHomeworkModal" :disabled="homeworkForm.processing"><i class="fa-solid fa-xmark"></i> Annuleren</button>
        </template>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import {debounce, pickBy} from "lodash";
import TeacherLayout from "@/Layouts/TeacherLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import moment from "moment/min/moment-with-locales";

export default {
    layout: TeacherLayout,
    props: {
        dates: Array,
        lessons: Object,
        week: String,
        data: Object
    },
    components: {
        Dialog
    },
    data() {
        return {
            params: {
                week: this.week,
                class: null,
                teacher: null
            },
            openModal: false,
            activeLesson: null,
            selectedClass: this.data.class,
            classes: [],
            isLoadingClasses: false,
            selectedTeacher: this.data.teacher,
            teachers: [],
            isLoadingTeachers: false,
            openHwModal: false,
            homeworkForm: useForm({
                lesson_id: null,
                type: "homework",
                description: null
            })
        }
    },
    watch: {
        params: {
            handler() {
                let params = pickBy(this.params);

                this.$inertia.get(this.route('teacher.schedule'), params, {
                    replace: true,
                    preserveState: true,
                    preserveScroll: true
                })
            },
            deep: true
        }
    },
    mounted() {
        this.asyncFindClasses();
        this.asyncFindTeachers();
    },
    methods: {
        getScheduleTimestamp(time) {
            time = time.replace(/ /g, '');
            let timeArray = time.split(':');
            let timeStamp = parseInt(timeArray[0]) * 60 + parseInt(timeArray[1]);
            return timeStamp;
        },
        singleLessonTop(startDate) {
            this.start = this.getScheduleTimestamp(startDate);
            let eventSlotHeight = 50;
            let timelineStart = 420;
            let timelineDuration = 30;

            // eventSlotHeight * (start - timelineStart) / timelineUnitDuration
            let start = eventSlotHeight * (this.start - timelineStart) / timelineDuration;
            return (start - 2);
        },
        singleLessonHeight(endDate) {
            this.duration = this.getScheduleTimestamp(endDate) - this.start;
            let eventSlotHeight = 50;
            let timelineDuration = 30;

            let end = eventSlotHeight * this.duration / timelineDuration;
            return (end - 2);
            // eventSlotHeight * duration / timeLineUnitDuration
        },
        nextWeek() {
            let weekNumber = this.week.split('-')[1];
            weekNumber++;
            this.params.week = this.week.split('-')[0] + '-' + weekNumber;
        },
        previousWeek() {
            let weekNumber = this.week.split('-')[1];
            weekNumber--;
            this.params.week = this.week.split('-')[0] + '-' + weekNumber;
        },
        openLessonModal(lesson) {
            this.activeLesson = lesson;
            this.openModal = true;
        },
        closeLessonModal() {
            this.activeLesson = null;
        },
        asyncFindClasses: debounce(function (query) {
            this.isLoadingClasses = true
            axios.get(this.route('teacher.schedules.getSchoolClasses', {
                query: query
            }))
                .then((response) => {
                    this.classes = response.data
                    this.isLoadingClasses = false
                })
        }, 150),
        selectClass(selectedClass) {
            // Unset teacher param
            this.params.teacher = null;
            this.selectedTeacher = null;

            this.params.class = selectedClass.name;
        },

        asyncFindTeachers: debounce(function (query) {
            this.isLoadingTeachers = true
            axios.get(this.route('teacher.schedules.getTeachers', {
                query: query
            }))
                .then((response) => {
                    this.teachers = response.data
                    this.isLoadingTeachers = false
                })
        }, 150),
        selectTeacher(selectedTeacher) {
            // Unset class param
            this.params.class = null;
            this.selectedClass = null;

            this.params.teacher = selectedTeacher.abbreviation;
        },

        openHomeworkModal() {
            this.homeworkForm.lesson_id = this.activeLesson.id;
            this.openHwModal = true;
        },
        closeHomeworkModal() {
            this.openHwModal = false;
        },
        addHomework() {
            this.homeworkForm.post(route('teacher.schedules.addHomework'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.openHwModal = false;
                    this.activeLesson.homework.push({
                        type: this.homeworkForm.type,
                        description: this.homeworkForm.description,
                        created_at: moment()
                    });
                    this.homeworkForm.reset();
                    this.toast('success', 'Huiswerk is toegevoegd!', '')
                },
            })
        },
        deleteHomework(id, index) {
            this.homeworkForm.delete(route('teacher.schedules.deleteHomework', id), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.activeLesson.homework.splice(index, 1);
                    this.toast('success', 'Huiswerk is verwijderd!', '');
                },
            })
        }
    }
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

#schedule-table {
    /* Reset */
    ol, ul {
        list-style: none;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    /* style css */
    a {
        color: #A2B9B2;
        text-decoration: none;
    }

    /* Main Components */
    .cd-schedule {
        position: relative;
        //margin: 2em 0;
    }

    .cd-schedule {
        width: 100%;
        max-width: 1600px;
        //margin: 2em auto;
    }

    .cd-schedule::after {
        clear: both;
        content: "";
        display: block;
    }

    /* Timeline */
    .cd-schedule .timeline {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        padding-top: 50px;
    }

    .cd-schedule .timeline ul {
        padding-left: 0 !important;
    }

    .cd-schedule .timeline li {
        position: relative;
        height: 50px;
        z-index: 2;
    }

    .cd-schedule .timeline li::after {
        /* this is used to create the table horizontal lines */
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: #EAEAEA;
    }

    .cd-schedule .timeline li::after {
        width: calc(100% - 48px);
        left: 48px;
    }

    .cd-schedule .timeline li span {
        display: inline-block;
        color: #575757;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    .cd-schedule .timeline li:nth-of-type(2n) span {
        display: none;
    }

    /* Events */
    .cd-schedule .lessons {
        position: relative;
    }

    .cd-schedule .lessons .lessons-group {
        margin-bottom: 30px;
        /* Standard blue background */
        background-color: #F4F9FF;
    }

    .cd-schedule .lessons .top-info {
        width: 100%;
        padding: 0 5%;
        background-color: #fff;
    }

    .cd-schedule .lessons .lessons-group > ul {
        position: relative;
        padding: 0 5%;
        z-index: 3;

    }

    .cd-schedule .lessons .single-lesson a {
        display: block;
        height: 100%;
        //padding: .8em;

        .lesson-icons {
            position: absolute;
            right: 8px;
            bottom: 8px;
        }
    }

    .cd-schedule .lessons {
        float: left;
        width: 100%;
    }

    .cd-schedule .lessons .lessons-group {
        width: 20%;
        float: left;
        border: 1px solid #EAEAEA;
        border-top: 0;
        border-bottom: 0;
        /* reset style */
        margin-bottom: 0;

        &:last-child {
            border-right: 0;
        }
    }

    .cd-schedule .lessons .lessons-group:not(:first-of-type) {
        border-left-width: 0;
    }

    .cd-schedule .lessons .top-info {
        /* vertically center its content */
        display: table;
        height: 50px;
        border-bottom: 1px solid #EAEAEA;
        /* reset style */
        padding: 0;
    }

    .cd-schedule .lessons .top-info span {
        display: inline-block;
        width: 100%;
        vertical-align: middle;
        text-align: center;

        &#day {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: -8px;
            text-transform: lowercase;

            &:first-letter {
                text-transform: uppercase;
            }
        }
    }

    .cd-schedule .lessons .lessons-group > ul {
        height: 950px;
        /* reset style */
        display: block;
        overflow: visible;
        padding: 0;
    }

    .cd-schedule .lessons .lessons-group > ul::after {
        clear: both;
        content: "";
        display: block;
    }

    .cd-schedule .lessons .lessons-group > ul::after {
        /* reset style */
        display: none;
    }

    .cd-schedule .lessons .single-lesson {
        position: absolute;
        z-index: 3;
        /* top position and height will be set using vue */
        width: 100%;
        /* reset style */
        -ms-flex-negative: 1;
        flex-shrink: 1;
        height: auto;
        max-width: none;
        margin-right: 0;
        overflow: hidden;
        white-space: nowrap;

        background-color: #fff;
        border-radius: 8px;
        border: 1px solid #f6f6f6;
    }

    .cd-schedule .lessons .single-lesson a {
        padding: 8px;
    }

    .cd-schedule .lessons .single-lesson:last-of-type {
        /* reset style */
        margin-right: 0;
    }

    .cd-schedule .lessons {
        /* 60px is the .timeline element width */
        //width: calc(100% - 60px);
        //margin-left: 60px;
        width: calc(100% - 16px);
        margin-left: 16px;
    }

    /* Single event */
    .cd-schedule .lesson-name {
        display: block;
        color: #000;
        font-weight: bold;
        font-size: 17px;
        line-height: 1.2rem;
    }

    .cd-schedule .lesson-abbreviation,
    .cd-schedule .lesson-classroom {
        display: block;
        color: #575757;
        line-height: 1.2rem;
    }

    .lesson-deleted {
        background-color: #FF6863 !important;
    }
}

#schedule-navigator {
    .multiselect {
        max-width: 250px;
    }
}
</style>

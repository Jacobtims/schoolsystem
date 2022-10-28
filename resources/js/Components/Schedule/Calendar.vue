<template>
    <FullCalendar
        ref="schedule"
        :options='calendarOptions'
    >
        <template v-slot:eventContent='arg'>
            <p class="lesson-subject">{{ arg.event.extendedProps.subject.name }}</p>
            <p class="lesson-classroom">{{ arg.event.extendedProps.classroom.name }}</p>
        </template>
    </FullCalendar>
</template>
<script>
import '@fullcalendar/core/vdom'; // solves problem with Vite
import FullCalendar from '@fullcalendar/vue3';
import timeGridPlugin from '@fullcalendar/timegrid';
import nlLocal from '@fullcalendar/core/locales/nl';

export default {
    components: {FullCalendar},
    props: {
        getEvents: Function,
    },
    data() {
        return {
            calendarOptions: {
                locale: nlLocal,
                plugins: [
                    timeGridPlugin
                ],
                headerToolbar: null,
                dayHeaderContent: this.dayHeaderContent,
                slotLabelFormat: this.slotLabelContent,
                nowIndicator: true,
                initialView: window.innerWidth < 1024 ? 'timeGridDay' : 'timeGridWeek',
                events: this.getEvents,
                dayMaxEvents: true,
                weekends: false,
                allDaySlot: false,
                eventClick: this.openLessonModal,
                slotDuration: '00:20:00',
                slotLabelInterval: '01:00',
                eventColor: '#4782f6'
            },
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
        openLessonModal(info) {
            this.$parent.openModal = true;
            this.$parent.lessonId = info.event.id;
        },
        dayHeaderContent({date}) {
            const dayjs = this.$dayjs(date);
            return {
                html: `<div class="day-header">
                           <h1 class="day-header-day">${dayjs.format('D')}</h1>
                           <h5 class="day-header-weekday">${dayjs.format('dddd')}</h5>
                       </div>`
            };
        },
        slotLabelContent({date}) {
            const time = date.hour + ':' + (date.minute === 0 ? '00' : date.minute);
            return time;
        }
    }
}
</script>

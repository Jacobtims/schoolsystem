<template>
    <FullCalendar
        ref="schedule"
        :options='calendarOptions'
        id="smallSchedule"
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
    props: {
        events: {
            required: true,
            default: [],
            type: Object
        }
    },
    components: {
        FullCalendar
    },
    data() {
        return {
            calendarOptions: {
                locale: nlLocal,
                plugins: [
                    timeGridPlugin
                ],
                headerToolbar: null,
                dayHeaderContent: null,
                slotLabelFormat: this.slotLabelContent,
                nowIndicator: true,
                initialView: 'timeGridDay',
                events: this.events,
                dayMaxEvents: true,
                allDaySlot: false,
                slotDuration: '00:20:00',
                slotLabelInterval: '01:00',
                eventColor: '#4782f6'
            },
        }
    },
    methods: {
        slotLabelContent({date}) {
            const time = date.hour + ':' + (date.minute === 0 ? '00' : date.minute);
            return time;
        }
    }
}
</script>

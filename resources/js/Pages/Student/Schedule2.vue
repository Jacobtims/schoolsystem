<template>
    <student-layout>
        <div id="schedule-table">
            <div class="cd-schedule js-full"> <!-- loading -->
                <div class="timeline">
                    <ul>
                        <li><span>07:00</span></li>
                        <li><span>07:30</span></li>
                        <li><span>08:00</span></li>
                        <li><span>08:30</span></li>
                        <!-- -->
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
                        <!--                        <li><span>16:30</span></li>-->
                        <!--                        <li><span>17:00</span></li>-->
                        <!--                        <li><span>17:30</span></li>-->
                        <!--                        <li><span>18:00</span></li>-->
                    </ul>
                </div> <!-- .timeline -->

                <div class="events">
                    <ul class="wrap">
                        <li class="events-group" v-for="date in dates">
                            <div class="top-info">
                                <span id="day">{{ this.$moment(date).format('dddd') }}</span>
                                <span>{{ this.$moment(date).format('DD/MM') }}</span>
                            </div>
                            <ul>
                                <template v-for="lesson in lessons[this.$moment(date).format('YYYY-MM-DD')]">
                                    <li class="single-event" v-for="(les, index) in lesson"
                                        :style="{'top': this.eventTop(les.time.from)+'px', 'height': this.eventHeight(les.time.to)+'px', 'width': (100 / lesson.length)+'%', 'left': (index * (100 / lesson.length))+'%'}">
                                        <a href="#0">
                                            <span class="event-name">{{ les.subject.name }}</span>
                                            <span class="event-abbreviation">NONE</span>
                                            <span class="event-classroom">E404</span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </student-layout>
</template>
<script>
// jQuery(document).ready(function($) {
//     function SchedulePlan(element) {
//         this.element = element;
//         this.timeline = this.element.find('.timeline');
//         this.timelineItems = this.timeline.find('li');
//         this.timelineItemsNumber = this.timelineItems.length;
//         this.timelineStart = getScheduleTimestamp(this.timelineItems.eq(0).text());
//         //need to store delta (in our case half hour) timestamp
//         this.timelineUnitDuration = getScheduleTimestamp(this.timelineItems.eq(1).text()) - getScheduleTimestamp(this.timelineItems.eq(0).text());
//
//         this.eventsWrapper = this.element.find('.events');
//         this.eventsGroup = this.eventsWrapper.find('.events-group');
//         this.singleEvents = this.eventsGroup.find('.single-event');
//         this.eventSlotHeight = this.eventsGroup.eq(0).children('.top-info').outerHeight();
//
//         console.log('eventSlotHeight: ' + this.eventSlotHeight);
//         console.log('timelineStart: ' + this.timelineStart);
//         console.log('timelineUnitDuration: ' + this.timelineUnitDuration);
//
//         this.animating = false;
//
//         this.initSchedule();
//     }
//
//     SchedulePlan.prototype.initSchedule = function () {
//         this.scheduleReset();
//         this.initEvents();
//     };
//
//     SchedulePlan.prototype.scheduleReset = function () {
//         var mq = this.mq();
//         if (mq == 'desktop' && !this.element.hasClass('js-full')) {
//             //in this case you are on a desktop version (first load or resize from mobile)
//             this.eventSlotHeight = this.eventsGroup.eq(0).children('.top-info').outerHeight();
//             this.element.addClass('js-full');
//             this.placeEvents();
//             // this.element.hasClass('modal-is-open') && this.checkEventModal();
//         } else if (mq == 'mobile' && this.element.hasClass('js-full')) {
//             //in this case you are on a mobile version (first load or resize from desktop)
//             this.element.removeClass('js-full loading');
//             this.eventsGroup.children('ul').add(this.singleEvents).removeAttr('style');
//             this.eventsWrapper.children('.grid-line').remove();
//             // this.element.hasClass('modal-is-open') && this.checkEventModal();
//         } else if (mq == 'desktop' && this.element.hasClass('modal-is-open')) {
//             //on a mobile version with modal open - need to resize/move modal window
//             this.checkEventModal('desktop');
//             this.element.removeClass('loading');
//         } else {
//             this.element.removeClass('loading');
//         }
//     };
//
//     SchedulePlan.prototype.initEvents = function () {
//         var self = this;
//
//         this.singleEvents.each(function () {
//             //create the .event-date element for each event
//             var durationLabel = '<span class="event-date">' + $(this).data('start') + ' - ' + $(this).data('end') + '</span>';
//             $(this).children('a').prepend($(durationLabel));
//         });
//     };
//
//     SchedulePlan.prototype.placeEvents = function () {
//         var self = this;
//         this.singleEvents.each(function () {
//             //place each event in the grid -> need to set top position and height
//             var start = getScheduleTimestamp($(this).attr('data-start')),
//                 duration = getScheduleTimestamp($(this).attr('data-end')) - start;
//
//             var eventTop = self.eventSlotHeight * (start - self.timelineStart) / self.timelineUnitDuration,
//                 eventHeight = self.eventSlotHeight * duration / self.timelineUnitDuration;
//
//             $(this).css({
//                 top: (eventTop - 1) + 'px',
//                 height: (eventHeight + 1) + 'px'
//             });
//         });
//
//         this.element.removeClass('loading');
//     };
//
//     SchedulePlan.prototype.mq = function () {
//         //get MQ value ('desktop' or 'mobile')
//         var self = this;
//         return window.getComputedStyle(this.element.get(0), '::before').getPropertyValue('content').replace(/["']/g, '');
//     };
//
//     var schedules = $('.cd-schedule');
//     var objSchedulesPlan = [],
//         windowResize = false;
//
//     if (schedules.length > 0) {
//         schedules.each(function () {
//             //create SchedulePlan objects
//             objSchedulesPlan.push(new SchedulePlan($(this)));
//         });
//     }
//
//     $(window).on('resize', function () {
//         if (!windowResize) {
//             windowResize = true;
//             (!window.requestAnimationFrame) ? setTimeout(checkResize) : window.requestAnimationFrame(checkResize);
//         }
//     });
//
//     function checkResize() {
//         objSchedulesPlan.forEach(function (element) {
//             element.scheduleReset();
//         });
//         windowResize = false;
//     }
//
//     function getScheduleTimestamp(time) {
//         //accepts hh:mm format - convert hh:mm to timestamp
//         time = time.replace(/ /g, '');
//         var timeArray = time.split(':');
//         var timeStamp = parseInt(timeArray[0]) * 60 + parseInt(timeArray[1]);
//         return timeStamp;
//     }
//
//     function transformElement(element, value) {
//         element.css({
//             '-moz-transform': value,
//             '-webkit-transform': value,
//             '-ms-transform': value,
//             '-o-transform': value,
//             'transform': value
//         });
//     }
// });

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
    methods: {
        getScheduleTimestamp(time) {
            time = time.replace(/ /g, '');
            let timeArray = time.split(':');
            let timeStamp = parseInt(timeArray[0]) * 60 + parseInt(timeArray[1]);
            return timeStamp;
        },
        eventTop(startDate) {
            this.start = this.getScheduleTimestamp(startDate);
            let eventSlotHeight = 50;
            let timelineStart = 420;
            let timelineDuration = 30;

            // eventSlotHeight * (start - timelineStart) / timelineUnitDuration
            let start = eventSlotHeight * (this.start - timelineStart) / timelineDuration;
            return (start - 1);
        },
        eventHeight(endDate) {
            this.duration = this.getScheduleTimestamp(endDate) - this.start;
            let eventSlotHeight = 50;
            let timelineDuration = 30;

            let end = eventSlotHeight * this.duration / timelineDuration;
            return (end - 1);
            // eventSlotHeight * duration / timeLineUnitDuration
        }
    }
}
</script>
<style lang="scss">
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
        max-width: 1400px;
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

    .cd-schedule .timeline li:last-of-type::after {
        display: none;
    }

    .cd-schedule .timeline li span {
        display: none;
    }

    @media only screen and (min-width: 1000px) {
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
    }

    /* Events */
    .cd-schedule .events {
        position: relative;
        z-index: 1;
    }

    .cd-schedule .events .events-group {
        margin-bottom: 30px;
    }

    .cd-schedule .events .top-info {
        width: 100%;
        padding: 0 5%;
    }

    //.cd-schedule .events .top-info > span {
    //    display: inline-block;
    //    line-height: 1.2;
    //    margin-bottom: 10px;
    //    font-weight: bold;
    //}

    .cd-schedule .events .events-group > ul {
        position: relative;
        padding: 0 5%;
        /* force its children to stay on one line */
        //display: -webkit-box;
        //display: -ms-flexbox;
        //display: flex;
        //overflow-x: scroll;
        //-webkit-overflow-scrolling: touch;
    }

    .cd-schedule .events .single-event a {
        display: block;
        height: 100%;
        //padding: .8em;
    }

    @media only screen and (min-width: 550px) {
        .cd-schedule .events .single-event {
            width: 40%;
        }
    }

    .cd-schedule .events {
        float: left;
        width: 100%;
    }

    .cd-schedule .events .events-group {
        width: 20%;
        float: left;
        border: 1px solid #EAEAEA;
        /* reset style */
        margin-bottom: 0;
    }

    .cd-schedule .events .events-group:not(:first-of-type) {
        border-left-width: 0;
    }

    .cd-schedule .events .top-info {
        /* vertically center its content */
        display: table;
        height: 50px;
        border-bottom: 1px solid #EAEAEA;
        /* reset style */
        padding: 0;
    }

    .cd-schedule .events .top-info span {
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

    .cd-schedule .events .events-group > ul {
        height: 950px;
        /* reset style */
        display: block;
        overflow: visible;
        padding: 0;
    }

    .cd-schedule .events .events-group > ul::after {
        clear: both;
        content: "";
        display: block;
    }

    .cd-schedule .events .events-group > ul::after {
        /* reset style */
        display: none;
    }

    .cd-schedule .events .single-event {
        position: absolute;
        z-index: 3;
        /* top position and height will be set using vue */
        width: 100%;
        //left: -1px;
        //box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), inset 0 -3px 0 rgba(0, 0, 0, 0.2);
        /* reset style */
        -ms-flex-negative: 1;
        flex-shrink: 1;
        height: auto;
        max-width: none;
        margin-right: 0;
        overflow: hidden;

        background-color: #fff;
        border-radius: 8px;
        border: 1px solid #f6f6f6;
    }

    .cd-schedule .events .single-event a {
        padding: 8px;
    }

    .cd-schedule .events .single-event:last-of-type {
        /* reset style */
        margin-right: 0;
    }

    .cd-schedule .events .single-event.selected-event {
        /* the .selected-event class is added when an user select the event */
        visibility: hidden;
    }

    @media only screen and (min-width: 1000px) {
        .cd-schedule .events {
            /* 60px is the .timeline element width */
            //width: calc(100% - 60px);
            //margin-left: 60px;
            width: calc(100% - 16px);
            margin-left: 16px;
        }
    }

    .cd-schedule.loading .events .single-event {
        /* the class .loading is added by default to the .cd-schedule element
             it is removed as soon as the single events are placed in the schedule plan (using javascript) */
        opacity: 0;
    }

    /* Single event */
    .cd-schedule .event-name {
        display: block;
        color: #000;
        font-weight: bold;
        font-size: 17px;
        line-height: 1.2rem;
    }

    .cd-schedule .event-abbreviation,
    .cd-schedule .event-classroom {
        display: block;
        color: #575757;
        line-height: 1.2rem;
    }

    //.cd-schedule .event-name {
    //    font-size: 2.4rem;
    //}
    //
    //.cd-schedule .event-name {
    //    font-size: 2rem;
    //}
    //
    //.cd-schedule .event-date {
    //    /* they are not included in the the HTML but added using JavScript */
    //    font-size: 1.4rem;
    //    opacity: .7;
    //    line-height: 1.2;
    //    margin-bottom: .2em;
    //}

    //.cd-schedule .single-event[data-event="event-1"] {
    //    /* this is used to set a background color for the event and the modal window */
    //    background: #577F92;
    //}
    //
    //.cd-schedule .single-event[data-event="event-1"]:hover {
    //    background: #618da1;
    //}
}
</style>

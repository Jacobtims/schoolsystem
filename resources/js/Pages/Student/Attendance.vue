<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <Card class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Niet geregistreerd</h2>
            <h6 class="text-sm font-light mb-1">Aantal lesuren</h6>
            <h3 class="text-3xl font-semibold">{{ notRegistered }} uur</h3>
        </Card>
        <Card class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Aanwezig</h2>
            <h6 class="text-sm font-light mb-1">Aantal lesuren</h6>
            <h3 class="text-3xl font-semibold">{{ present }} uur</h3>
        </Card>
        <Card class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Geoorloofd afwezig</h2>
            <h6 class="text-sm font-light mb-1">Aantal lesuren</h6>
            <h3 class="text-3xl font-semibold">{{ allowedAbsent }} uur</h3>
        </Card>
        <Card class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Ongeoorloofd afwezig</h2>
            <h6 class="text-sm font-light mb-1">Aantal lesuren</h6>
            <h3 class="text-3xl font-semibold text-dark-red">{{ unallowedAbsent }} uur</h3>
        </Card>
    </div>

    <Card v-if="absences.data.length > 0">
        <div class="overflow-x-auto relative">
            <table class="table-auto w-full text-left mb-4" id="absencesTable">
                <thead class="text-sm uppercase bg-slate-50 border-b">
                <tr>
                    <th>Datum</th>
                    <th>Les</th>
                    <th>Type</th>
                    <th>Reden</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(absence, index) in absences.data" :key="'absent'+index" class="odd:bg-white even:bg-slate-50">
                    <td>{{ this.$dayjs(absence.lesson.date).format('LL') }}</td>
                    <td>{{ absence.lesson.time.id }}e lesuur ({{ absence.lesson.time.from }} - {{
                            absence.lesson.time.to
                        }})
                    </td>
                    <td>{{ absence.type ? absence.type.name : '-' }}</td>
                    <td>{{ absence.reason ?? '-' }}</td>
                    <td v-if="absence.reason_verified">Geoorloofd</td>
                    <td v-else-if="!absence.reason_verified" class="text-danger">Ongeoorloofd</td>
                </tr>
                </tbody>
            </table>
        </div>

        <Pagination :pagination="absences"/>
    </Card>
    <Card v-else>
        <p>Geen meldingen gevonden!</p>
    </Card>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Card from '@/Components/Card.vue';

export default {
    components: {Pagination, Card},
    layout: StudentLayout,
    props: {
        present: Number,
        allowedAbsent: Number,
        unallowedAbsent: Number,
        notRegistered: Number,
        absences: Object
    }
}
</script>
<style lang="scss">
#absencesTable {
    tr th {
        @apply py-3 px-6;
    }
    tr td {
        @apply py-4 px-6;
    }
}
</style>

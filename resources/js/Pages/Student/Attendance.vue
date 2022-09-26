<template>
    <div class="row">
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Niet geregistreerd</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold">{{ notRegistered }} uur</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Aanwezig</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold">{{ present }} uur</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Geoorloofd afwezig</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold">{{ allowedAbsent }} uur</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Ongeoorloofd afwezig</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold text-danger">{{ unallowedAbsent }} uur</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3" v-if="absences.data.length > 0">
        <div class="card-body">
            <table class="table table-borderless m-0 mb-4" id="table-absent">
                <tr>
                    <th>Datum</th>
                    <th>Les</th>
                    <th>Type</th>
                    <th>Reden</th>
                    <th>Status</th>
                </tr>
                <tr v-for="(absence, index) in absences.data" :key="'absent'+index">
                    <td>{{ this.$dayjs(absence.lesson.date).format('LL') }}</td>
                    <td>{{ absence.lesson.time.id }}e lesuur ({{ absence.lesson.time.from }} - {{ absence.lesson.time.to }})</td>
                    <td>{{ absence.type ? absence.type.name : '-'}}</td>
                    <td>{{ absence.reason ?? '-' }}</td>
                    <td v-if="absence.reason_verified">Geoorloofd</td>
                    <td v-else-if="!absence.reason_verified" class="text-danger">Ongeoorloofd</td>
                </tr>
            </table>

            <Pagination :pagination="absences"/>
        </div>
    </div>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout.vue";
import Pagination from "@/Components/Pagination.vue";
export default {
    components: {Pagination},
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
#table-absent {
    font-size: 18px;
}
</style>

<template>
    <div class="row">
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Aanwezig</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold">{{ counts.present }} uur</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Geoorloofd afwezig</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold">{{ counts.authorizedAbsent }} uur</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Ongeoorloofd afwezig</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold text-danger">{{ counts.unauthorizedAbsence }} uur</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Niet geregistreerd</h3>
                    <small>Aantal lesuren</small>
                    <h3 class="fw-bold">-1 uur</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-borderless m-0" id="table-absent">
                <tr>
                    <th>Datum</th>
                    <th>Les</th>
                    <th>Status</th>
                    <th>Reden</th>
                </tr>
                <tr v-for="(abs, index) in absent" :key="'absent'+index">
                    <td>{{ this.$moment(abs.lesson.date).format('LL') }}</td>
                    <td>{{ abs.lesson.time.from }} - {{ abs.lesson.time.to }}</td>
                    <td v-if="abs.verified === 1">Geoorloofd</td>
                    <td v-else-if="abs.verified === 0 || abs.verified === null" class="text-danger">Ongeoorloofd</td>
                    <td>{{ abs.reason ?? 'Onbekend' }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout.vue";
export default {
    layout: StudentLayout,
    props: {
        counts: Object,
        absent: Object
    }
}
</script>
<style lang="scss">
#table-absent {
    font-size: 18px;
}
</style>

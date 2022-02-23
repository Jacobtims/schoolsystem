<template>
    <student-layout>
        <div class="row">
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
                        <h3 class="fw-bold">{{ authorizedAbsent }} uur</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 col-xl-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title">Ongeoorloofd afwezig</h3>
                        <small>Aantal lesuren</small>
                        <h3 class="fw-bold text-danger">{{ unauthorizedAbsence }} uur</h3>
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
    </student-layout>
</template>
<script>
import StudentLayout from "@/Layouts/StudentLayout";
export default {
    props: {
        present: Number,
        authorizedAbsent: Number,
        unauthorizedAbsence: Number
    },
    components: {
        StudentLayout
    }
}
</script>

<template>
    <h2 class="mb-3">Admin dashboard</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="col-md-12 card text-white bg-success">
                <div class="card-body">
                    <h1>{{ counts.students ?? 0 }}</h1>
                    <h5>Studenten</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="col-md-12 card text-white bg-danger">
                <div class="card-body">
                    <h1>{{ counts.teachers ?? 0 }}</h1>
                    <h5>Docenten</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="col-md-12 card text-white bg-primary">
                <div class="card-body">
                    <h1>-1</h1>
                    <h5>Ouders</h5>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout";
export default {
    layout: AdminLayout,
    props: {
        counts: Object
    },
    components: {
    }
}
</script>

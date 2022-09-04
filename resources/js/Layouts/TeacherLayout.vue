<template>
    <div class="container-fluid" id="studentBackground">
        <div class="row flex-nowrap">
            <sidebar/>

            <div class="col">
                <navbar/>

                <main class="py-3">
                    <slot></slot>
                </main>
            </div>
        </div>
    </div>
    <Toast position="bottom-right"/>
</template>
<script>
import Sidebar from "@/Layouts/Teacher/Sidebar.vue";
import Navbar from "@/Layouts/Teacher/Navbar.vue";
export default {
    components: {
        Navbar,
        Sidebar
    }
}
</script>

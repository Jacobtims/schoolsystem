<template>
    <div class="container-fluid" id="adminBackground">
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
</template>
<script>
import Sidebar from "@/Pages/Admin/components/Sidebar";
import Navbar from "@/Pages/Admin/components/Navbar";

export default {
    components: {
        Navbar,
        Sidebar
    }
}
</script>

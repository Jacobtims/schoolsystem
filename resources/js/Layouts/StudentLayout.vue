<template>
    <div class="flex w-full min-h-screen bg-gray-100">
        <Sidebar/>
        <div class="w-full flex flex-col h-screen">
            <Navbar/>

            <main class="p-10 flex-1 overflow-auto">
                <slot/>
            </main>
        </div>
    </div>

    <Toast position="bottom-right"/>
</template>
<script>
import Sidebar from "@/Layouts/Student/Sidebar.vue";
import Navbar from "@/Layouts/Student/Navbar.vue";

export default {
    components: {
        Navbar,
        Sidebar
    }
}
</script>

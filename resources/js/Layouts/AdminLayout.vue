<template>
    <div id="adminBackground" style="height: 100vh;">
        <navbar/>
        <sidebar/>
        <main id="admin-main" class="py-3">
            <slot></slot>
        </main>
    </div>
</template>
<script>
import Sidebar from "@/Layouts/Admin/Sidebar";
import Navbar from "@/Layouts/Admin/Navbar";
export default {
    components: {
        Navbar,
        Sidebar
    }
}
</script>
<style lang="scss">
#adminBackground {
    #admin-main {
        transition: .5s;
        padding-left: calc(68px /* Width of sidebar */ + 16px);
        padding-right: 16px;
    }
    .body-pd {
        padding-left: calc(68px /* Width of sidebar */ + 16px);
    }
}
@media screen and (min-width: 768px) {
    #adminBackground {
        .body-pd {
            padding-left: calc(260px /* Width of sidebar */ + 16px) !important;
        }
    }
}
</style>

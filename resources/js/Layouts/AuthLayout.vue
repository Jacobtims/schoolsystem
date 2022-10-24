<template>
    <main class="h-screen">
        <div class="flex h-screen">
            <div class="hidden md:block md:w-1/2 xl:w-2/3" id="left-side">
                <!-- TODO: Background & text -->
                <!-- <p class="text-center text-xs mt-5 text-white">-->
                <!--     Copyright &copy; {{ currentYear }} - Jacob Timmerman-->
                <!-- </p>-->
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 flex flex-col justify-center p-5 md:p-16">
                <slot/>
            </div>
        </div>
    </main>
</template>
<style>
#left-side {
    background: #8060C5;
}
</style>

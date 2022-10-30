<template>
    <div class="flex flex-col gap-4 max-h-80 overflow-y-auto">
        <div class="flex items-center justify-between border border-gray-200 rounded-lg py-2 px-3" v-for="grade in grades">
            <div>
                <h1 class="text-lg font-semibold -mb-0.5">{{ grade.assignment.name }}</h1>
                <h2 class="text-sm mb-3">{{ grade.assignment.subject.name }}</h2>
                <p class="font-light text-xs first-letter:uppercase">{{ $dayjs(grade.created_at).format('dddd, LL') }}</p>
            </div>

            <p class="text-4xl font-medium text-primary-500">{{ grade.number }}</p>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        grades: Object
    }
}
</script>

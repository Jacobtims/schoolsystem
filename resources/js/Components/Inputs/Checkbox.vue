<template>
    <div class="flex items-center space-x-1.5">
        <input class="rounded border-gray-300 text-primary-700 focus:ring-primary-600"
               type="checkbox"
               :id="id"
               :checked="modelValue"
               @input="$emit('update:modelValue', $event.target.checked)">
        <label :for="id" class="ml-2 text-gray-900 text-sm">
            <slot/>
        </label>
    </div>
</template>
<script>
export default {
    props: {
        modelValue: {
            default: null,
            type: Boolean
        },
        id: {
            required: true,
            type: String
        }
    }
}
</script>

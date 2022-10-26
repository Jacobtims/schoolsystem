<template>
    <input
        class="w-full shadow-sm text-gray-700 border-gray-300 rounded-lg placeholder:text-gray-400 focus:border-primary-600 focus:ring-primary-600"
        :class="{'border-dark-red invalid:border-dark-red': invalid}"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        v-bind="$attrs"/>
    <span class="text-xs text-red-600" v-if="invalid">
        {{ invalid }}
    </span>
</template>
<script>
export default {
    props: {
        inheritAttrs: false,
        modelValue: {
            default: "",
            type: [String, Number]
        },
        invalid: {
            default: false,
            type: [String, Boolean]
        }
    }
}
</script>

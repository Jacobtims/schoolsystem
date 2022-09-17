<template>
    <label :for="id" class="form-label">{{ label }}</label>
    <select class="form-select" :class="{'is-invalid': error}" :aria-describedby="feedback" :id="id"
            :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" v-bind="$attrs">
        <slot/>
    </select>
    <div :id="feedback" class="invalid-feedback" v-if="error">
        {{ error }}
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        modelValue: {
            default: "",
            type: [String, Number]
        },
        label: String,
        error: String
    },
    computed: {
        id() {
            return "input" + this.label;
        },
        feedback() {
            return "feedback" + (this.id[0].toUpperCase() + this.id.slice(1));
        }
    }
}
</script>

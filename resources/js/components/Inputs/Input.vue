<template>
    <label :for="id" class="form-label">{{ label }}</label>
    <input class="form-control" :class="{'is-invalid': error}" :aria-describedby="feedback" :id="id"
           :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" v-bind="$attrs">
    <div :id="feedback" class="invalid-feedback" v-if="error">
        {{ error }}
    </div>
    <slot/>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        modelValue: {
            default: "",
            type: String
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

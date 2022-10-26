<template>
    <div class="form-check">
        <input class="form-check-input" :class="{'is-invalid': error}" type="checkbox" :aria-describedby="feedback" :id="id"
               :checked="modelValue" @input="$emit('update:modelValue', $event.target.checked)" v-bind="$attrs">
        <label class="form-check-label" :for="id">{{ label }}</label>
    </div>
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
            default: null,
            type: Boolean
        },
        label: String,
        error: String
    },
    computed: {
        id() {
            return "checkbox" + this.label;
        },
        feedback() {
            return "feedback" + (this.id[0].toUpperCase() + this.id.slice(1));
        }
    }
}
</script>

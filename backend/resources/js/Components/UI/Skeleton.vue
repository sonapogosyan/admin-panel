<script setup>
defineProps({
    class: String,
});
</script>

<template>
    <div :class="['animate-pulse bg-slate-200 rounded', $props.class]"></div>
</template>

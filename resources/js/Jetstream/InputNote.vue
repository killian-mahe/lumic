<template>
    <div v-show="message">
        <p class="text-xs text-gray-700">
            {{ message }}
        </p>
    </div>
</template>

<script>
export default {
    name: "InputNote",
    props: ['message']
}
</script>

<style scoped>

</style>

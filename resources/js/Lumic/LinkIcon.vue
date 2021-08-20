<template>
    <img v-if="url" :src="url" :alt="link.name" :class="computedSize" class="mt-2 rounded-full object-cover"/>
</template>

<script>
export default {
    name: "LinkIcon.vue",
    props: ['link', 'size'],
    computed : {
        url() {
            return this.link.favicon_url
        },
        computedSize() {
            switch (this.size) {
                case 'xs':
                    return {'h-5 w-5': true}
                case 'sm':
                    return {'h-10 w-10': true}
                case 'md':
                    return {'h-20 w-20': true}
                default:
                    return {'h-10 w-10': true}
            }
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" :class="computedClass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
</template>

<script>
export default {
    name: "ChevronIcon.vue",
    props: {
        direction: {
            default: 'down'
        },
        size: {
            default: 24
        }
    },
    computed: {
        directionClass() {
            switch (this.direction) {
                case 'up':
                    return 'transform rotate-180';
                case 'down':
                    return '';
                case 'left':
                    return 'transform rotate-90';
                case 'right':
                    return 'transform -rotate-90';
                default:
                    return ''
            }
        },
        computedClass() {
            return `feather feather-chevron-down w-${this.size} h-${this.size} ${this.directionClass}`
        }
    }
}
</script>

<style scoped>

</style>

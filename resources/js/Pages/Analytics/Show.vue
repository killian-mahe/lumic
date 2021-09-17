<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics - {{ $page.props.user.current_team.name }}
            </h2>
        </template>


    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';

export default {
    components: {
        AppLayout,
    },
    mounted() {
        console.log(this.$page.props.statistics)
    }
}
</script>

<style scoped>

</style>

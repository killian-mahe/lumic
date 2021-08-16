<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">


        <div class="h-screen flex flex-col justify-around sm:block sm:h-auto max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-center pt-8 sm:pt-0 items-center space-x-6">
                <application-mark class="h-16 w-auto text-gray-500 sm:h-20"></application-mark>
                <span class="text-gray-500 dark:text-gray-500 text-6xl">Lumic</span>
            </div>


            <div class="mt-16 flex border-black items-center flex-col text-gray-500 dark:text-gray-500">
                <div class="mb-4 font-bold text-center">Reduce your URLs and share them with your teams !</div>
                <div class=" mt-10 flex flex-col items-center sm:mt-0 sm:block">
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="font-extralight text-sm">https://www.youtube.com/watch?v=dQw4w9WgXcQ</a>
                    <chevron-icon size="5" class="inline sm:hidden mx-2"/>
                    <chevron-icon size="5" direction="right" class="hidden sm:inline mx-2"/>
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="underline">lumic.fr/<span class="font-extrabold">yt</span></a>
                </div>
            </div>

            <div v-if="canLogin" class="flex justify-center mt-32">
                <inertia-link v-if="$page.props.user" href="/dashboard" class="underline text-gray-400 dark:text-gray-400">
                    Dashboard
                </inertia-link>

                <template v-else>
                    <inertia-link :href="route('login')" class="underline text-gray-400 dark:text-gray-400">
                        Log in
                    </inertia-link>

                    <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 underline text-gray-400 dark:text-gray-400">
                        Register
                    </inertia-link>
                </template>
            </div>

        </div>

    </div>
</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-500 {
            color: #D6DBDF;
            color: rgba(214, 219, 223, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #AEB6BF;
            color: rgba(174, 182, 191, var(--tw-text-opacity));
        }
    }
</style>

<script>
    import ApplicationMark from '@/Jetstream/ApplicationMark'
    import ChevronIcon from "@/Icons/ChevronIcon";

    export default {
        components: {
            ApplicationMark,
            ChevronIcon
        },
        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
        }
    }
</script>

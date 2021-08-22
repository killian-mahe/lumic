<template>
    <jet-action-section>
        <template #title>
            Website Management
        </template>

        <template #description>
            Check website services status and update the website version.
        </template>

        <template #content>
            <div class="grid grid-cols-2">

                <div>
                    Update status
                </div>
                <div>
                    <div v-if="$page.props.commit_diff == null">
                        <span class="text-yellow-500" >Unknown</span>
                        <br/>
                        <span class="text-sm text-gray-600">Error message : </span><span class="text-xs text-red-400">{{$page.props.error_msg}}</span>
                    </div>

                    <span v-else-if="$page.props.commit_diff == 0" class="text-green-500">Up to date</span>

                    <div v-else>
                        <span class="text-red-400">The production website isn't up to date.</span>
                        <br/>
                        <span class="text-sm text-gray-600">Commit pending : {{$page.props.commit_diff}}</span>
                        <br/>
                        <jet-secondary-button :disabled="form.processing" @click="updateWebsite">Update</jet-secondary-button>
                    </div>
                </div>

            </div>
        </template>
    </jet-action-section>
</template>

<script>
import JetActionSection from '@/Jetstream/ActionSection'
import JetButton from '@/Jetstream/Button'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
    name: "WebsiteManagement",
    components: {
        JetActionSection,
        JetButton,
        JetSecondaryButton
    },
    data() {
        return {
            form: this.$inertia.form({})
        }
    },
    methods: {
        updateWebsite() {
            this.form.post(route('admin.update'));
        }
    }
}
</script>

<style scoped>

</style>

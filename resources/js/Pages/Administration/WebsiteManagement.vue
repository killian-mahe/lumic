<template>
    <jet-action-section>
        <template #title>
            Website Management
        </template>

        <template #description>
            Check website services status and update the website version.
        </template>

        <template #content>
            <div class="grid grid-cols-2 gap-y-3">

                <div>
                    Git branch
                </div>
                <div>
                    <jet-dropdown align="left" direction="down" width="80">
                        <template #trigger>
                                <span class="inline-flex rounded-md w-full">
                                    <button type="button" id="link_visibility" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition">
                                        {{ selectedBranch }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                        </template>

                        <template #content>
                            <div>
                                <jet-dropdown-link as="button" v-for="branch in $page.props.available_branches" @click="selectBranch(branch)">{{branch}}</jet-dropdown-link>
                            </div>
                        </template>
                    </jet-dropdown>
                </div>

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
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'

export default {
    name: "WebsiteManagement",
    components: {
        JetActionSection,
        JetButton,
        JetSecondaryButton,
        JetDropdown,
        JetDropdownLink
    },
    data() {
        return {
            selectedBranch: this.$page.props.current_branch,
            form: this.$inertia.form({
                branch: this.$page.props.current_branch
            })
        }
    },
    mounted() {
      this.$inertia.get(route('git.currentBranch'),
      onSuccess: () => )
    },
    methods: {
        updateWebsite() {
            this.form.post(route('admin.update'));
        },
        selectBranch(branch) {
            this.selectedBranch = branch;
            this.form.branch = branch;
            console.log("Sending form");
            this.form.post(route('admin.changeCurrentBranch'));
        }
    }
}
</script>

<style scoped>

</style>

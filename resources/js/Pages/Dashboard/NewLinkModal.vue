<template>
    <jet-dialog-modal @close="close">

        <template #title>
            New link
        </template>

        <template #content>
            <div class="grid grid-cols-1 gap-y-3">

                <div>
                    <jet-label for="link_name">Name</jet-label>
                    <jet-input type="text" id="link_name" class="mt-1 block w-full" v-model="form.name"></jet-input>
                    <jet-input-error :message="$page.props.errors.name"></jet-input-error>
                </div>

                <div>
                    <jet-label for="link_value">Value</jet-label>
                    <jet-input type="text" id="link_value" class="mt-1 block w-full" v-model="form.value"></jet-input>
                    <jet-input-error :message="$page.props.errors.value"></jet-input-error>
                </div>

                <div>
                    <jet-label for="link_visibility">Visibility</jet-label>
                    <jet-dropdown align="left" direction="up" width="40" class="mt-1" v-if="$page.props.jetstream.hasTeamFeatures">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button" id="link_visibility" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition">
                                    {{visibility}}

                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="w-60">
                                <jet-dropdown-link as="button" @click="onVisibilitySelect(true)">Public</jet-dropdown-link>
                                <jet-dropdown-link as="button" @click="onVisibilitySelect(false)">Private</jet-dropdown-link>
                            </div>
                        </template>
                    </jet-dropdown>
                </div>

            </div>
        </template>

        <template #footer>
            <div class="flex justify-end space-x-2">

                <jet-dropdown align="right" direction="up" width="40" v-if="$page.props.jetstream.hasTeamFeatures">
                    <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button" id="link_team" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition">
                                    {{ selectedTeam.name }}

                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                    </template>

                    <template #content>
                        <div class="w-60">
                            <div v-for="team in selectableTeams">
                                <jet-dropdown-link as="button" @click="onTeamSelect(team)">{{team.name}}</jet-dropdown-link>
                            </div>
                        </div>
                    </template>
                </jet-dropdown>

                <jet-button :disabled="form.processing" @click="newLink">Create</jet-button>

            </div>
        </template>

    </jet-dialog-modal>
</template>

<script>
import JetDialogModal from '@/Jetstream/DialogModal'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from '@/Jetstream/DangerButton'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'

export default {
    name: "NewLinkModal",
    emits: ['close', 'success'],
    components: {
        JetDialogModal,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
        JetDangerButton,
        JetDropdown,
        JetDropdownLink
    },
    data() {
        return {
            form : this.$inertia.form({
                name: "",
                value: "",
                team: "",
                visibility: false
            }),
            selectedTeam: this.$page.props.user.owned_teams[0]
        }
    },
    computed: {
        selectableTeams() {
            return this.$page.props.user.teams.filter(team => {
                return this.$page.props.teams_permissions[team.id].includes('links:create');
            }).concat(this.$page.props.user.owned_teams);
        },
        visibility() {
            if (this.form.visibility) return "Public"
            return "Private"
        }
    },
    methods: {
        close() {
            this.$emit('close')
        },
        newLink() {
            this.form.team = this.selectedTeam.id;
            this.form.post(route('link.store'), {
                onSuccess: () => this.$emit('success')
            });
        },
        onTeamSelect(team) {
            this.selectedTeam = team;
        },
        onVisibilitySelect(visibility) {
            this.form.visibility = visibility;
        }
    }
}
</script>

<style scoped>

</style>

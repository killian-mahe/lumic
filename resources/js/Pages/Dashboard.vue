<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard - {{ $page.props.user.current_team.name }}
                </h2>
            </template>

            <edit-link-modal :show="isEditing" @close="closeEdition" :link="currentLink"></edit-link-modal>

            <new-link-modal :show="isCreating" @close="isCreating = false" @success="newSuccess"></new-link-modal>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    <div class="px-2 sm:px-0 w-full mb-4 flex">
                        <jet-input type="text" v-model="searchQuery" placeholder="Search" class="flex-grow"></jet-input>

                        <jet-button type="button" class="ml-4" @click="isCreating = true">New</jet-button>
                    </div>

                    <action-message :on="actionMessage">
                        {{actionMessage}}
                    </action-message>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">

                        <div v-for="link in filteredLinks" :key="'link_'+link.id">

                            <div class="p-4 flex flex-col bg-white shadow-lg sm:rounded-lg">

                                <span class="font-semibold text-xl flex justify-center items-center">
                                    {{link.name}}
                                    <lock-icon class="ml-2" size="4" v-if="!link.public"/>
                                </span>

                                <lumic-link-icon :link="link" size="md" class="mx-auto"/>

                                <a :href="link.value" target="_blank" class="mt-4 truncate text-gray-500 text-center hover:underline">{{link.value}}</a>

                                <div class="flex justify-center mt-6 space-x-4">
                                    <jet-button type="button" @click="edit(link)" v-if="canEdit">Edit</jet-button>
                                    <jet-link :href="link.value" target="_blank">Go</jet-link>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </app-layout>
    </div>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
    import JetInput from '@/Jetstream/Input'
    import JetButton from '@/Jetstream/Button'
    import JetLink from '@/Jetstream/Link'
    import EditLinkModal from "@/Pages/Dashboard/EditLinkModal";
    import NewLinkModal from "@/Pages/Dashboard/NewLinkModal";
    import ActionMessage from "@/Jetstream/ActionMessage";
    import LockIcon from "@/Icons/LockIcon";
    import UsersIcon from "@/Icons/UsersIcon";
    import LumicLinkIcon from "@/Lumic/LinkIcon"

    export default {
        components: {
            ActionMessage,
            JetApplicationLogo,
            AppLayout,
            Welcome,
            JetInput,
            JetButton,
            JetLink,
            EditLinkModal,
            NewLinkModal,
            LockIcon,
            UsersIcon,
            LumicLinkIcon
        },
        props: {
            current_links: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                isEditing: false,
                isCreating: false,
                actionMessage: "",
                searchQuery: "",
                currentLink: null,
                form : this.$inertia.form({
                    _method: 'PUT',
                    name: "",
                    value: "",
                    visibility: false,
                    favicon: null,
                    id: 0
                })
            }
        },
        computed: {
            filteredLinks() {
              return this.current_links.filter(link => {
                  return link.name.includes(this.searchQuery) || link.value.includes(this.searchQuery);
              })
            },
            canEdit() {
                return this.$page.props.teams_permissions[this.$page.props.user.current_team.id].includes('links:update')
                        || this.isCurrentTeamOwner;
            },
            isCurrentTeamOwner() {
                return this.$page.props.user.owned_teams.map(t => t.id).includes(this.$page.props.user.current_team.id)
            }
        },
        methods: {
            newSuccess() {
                this.isCreating = false;
                this.actionMessage = "New link added successfully !"
                setTimeout(() => {
                    this.actionMessage = ""
                }, 3000);
            },
            closeEdition() {
                this.isEditing = false;
                this.form.reset();
                this.currentLink = null;
            },
            edit(link) {
                this.currentLink = link;
                this.isEditing = true;
            }
        }
    }
</script>

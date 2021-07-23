<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard - {{ $page.props.user.current_team.name }}
                </h2>
            </template>

            <edit-link-modal :show="isEditing" @close="closeEdition" @delete="deleteLink" @save="updateLink" v-model="form"></edit-link-modal>

            <new-link-modal :show="isCreating" @close="isCreating = false" @success="newSuccess"></new-link-modal>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    <div class="w-full mb-4 flex">
                        <jet-input type="text" v-model="searchQuery" placeholder="Search" class="flex-grow"></jet-input>

                        <jet-button type="button" class="ml-4" @click="isCreating = true">New</jet-button>
                    </div>

                    <action-message :on="actionMessage">
                        {{actionMessage}}
                    </action-message>

                    <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-2">

                        <div v-for="link in filteredLinks" :key="'link_'+link.id">

                            <div class="p-4 flex flex-col bg-white shadow-lg sm:rounded-lg">

                                <span class="font-semibold text-xl text-center">{{link.name}}</span>

                                <a :href="link.value" class="mt-8 truncate text-gray-500 text-center hover:underline">{{link.value}}</a>

                                <div class="flex justify-around mt-8" v-if="canEdit">
                                    <jet-button type="button" @click="edit(link)">Edit</jet-button>
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
    import EditLinkModal from "@/Pages/Dashboard/EditLinkModal";
    import NewLinkModal from "@/Pages/Dashboard/NewLinkModal";
    import ActionMessage from "@/Jetstream/ActionMessage";

    export default {
        components: {
            ActionMessage,
            JetApplicationLogo,
            AppLayout,
            Welcome,
            JetInput,
            JetButton,
            EditLinkModal,
            NewLinkModal
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
                form : this.$inertia.form({
                    name: "",
                    value: "",
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
            updateLink() {
                this.form.put(route('link.update', this.form.id), {
                    onSuccess: () => this.closeEdition()
                })
            },
            deleteLink() {
                this.form.delete(route('link.destroy', this.form.id), {
                    onSuccess: () => this.closeEdition()
                })
            },
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
            },
            edit(link) {
                this.form.name = link.name;
                this.form.value = link.value;
                this.form.id = link.id;
                this.isEditing = true;
            }
        }
    }
</script>

<template>
    <jet-dialog-modal @close="close">

        <template #title>
            Edit link
        </template>

        <template #content>
            <div class="grid grid-cols-1 gap-y-6">
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
                    <!-- Profile Photo File Input -->
                    <input type="file" class="hidden"
                           ref="favicon" accept="image/png, image/jpeg"
                           @change="updateFaviconPreview">

                    <jet-label for="favicon" value="Favicon" />

                    <!-- Current Profile Photo -->
                    <div class="mt-2" v-show="!faviconPreview && link.favicon_url && !form.delete_favicon">
                        <lumic-link-icon :link="link" size="md"/>
                    </div>
                    <div v-show="(!faviconPreview && !link.favicon_url) || form.delete_favicon">
                        <span>
                            No favicon has been set for this link
                        </span>
                    </div>

                    <div class="mt-2" v-show="faviconPreview">
                        <span class="block rounded-full w-20 h-20"
                              :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + faviconPreview + '\');'">
                        </span>
                    </div>

                    <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewFavicon">
                        Select A New Picture
                    </jet-secondary-button>
                    <jet-secondary-button class="mt-2 mr-2" v-show="(faviconPreview || link.favicon_url) && !form.delete_favicon" type="button" @click.prevent="deleteFavicon">
                        Delete The Favicon
                    </jet-secondary-button>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="flex justify-end space-x-2">
                <jet-dropdown align="right" direction="up" width="40" v-if="$page.props.jetstream.hasTeamFeatures">
                    <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button" id="link_visibility" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition">
                                        <users-icon class="mr-2 inline" size="4" v-if="form.visibility"/>
                                        <lock-icon class="mr-2 inline" size="4" v-else/>
                                        {{visibility}}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                    </template>

                    <template #content>
                        <div>
                            <jet-dropdown-link as="button" @click="onVisibilitySelect(true)"><users-icon class="mr-2 inline" size="4"/> Public</jet-dropdown-link>
                            <jet-dropdown-link as="button" @click="onVisibilitySelect(false)"><lock-icon class="mr-2 inline" size="4"/> Private</jet-dropdown-link>
                        </div>
                    </template>
                </jet-dropdown>
                <jet-danger-button v-if="canDelete" :disabled="form.processing" @click="deleteLink">Delete</jet-danger-button>
                <jet-button :disabled="form.processing" v-if="canEdit" @click="updateLink">Save</jet-button>
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
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetDangerButton from '@/Jetstream/DangerButton'
import LockIcon from "@/Icons/LockIcon";
import UsersIcon from "@/Icons/UsersIcon";
import LumicLinkIcon from "@/Lumic/LinkIcon"

export default {
    name: "EditLinkModal",
    emits: ['close', 'delete', 'save'],
    components: {
        JetDialogModal,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        JetDangerButton,
        JetDropdown,
        JetDropdownLink,
        LockIcon,
        UsersIcon,
        LumicLinkIcon
    },
    props: {
        link: {
            default: () => {}
        }
    },
    data() {
      return {
          faviconPreview: null,
          form : this.$inertia.form({
              _method: 'PUT',
              name: "",
              value: "",
              visibility: "",
              favicon: null,
              delete_favicon: false,
              id: 0
          })
      }
    },
    beforeUpdate() {
        if (this.link) {
            this.form.name = this.link.name;
            this.form.value = this.link.value;
            this.form.visibility = this.link.public;
            this.form.delete_favicon = false;
            this.form.id = this.link.id;
        } else {
            this.reset();
        }
    },
    methods: {
        updateLink() {
            this.form.post(route('link.update', this.form.id), {
                onSuccess: () => {
                    this.reset();
                    this.close();
                }
            })
        },
        deleteLink() {
            this.form.delete(route('link.destroy', this.form.id), {
                onSuccess: () => {
                    this.reset();
                    this.close();
                }
            })
        },
        reset() {
            this.form.reset();
            this.faviconPreview = null;
            this.$refs.favicon.value = null;
        },
        deleteFavicon() {
            this.faviconPreview = null;
            this.form.favicon = null;
            this.$refs.favicon.value = null;
            if (this.link.favicon_url) this.form.delete_favicon = true;
        },
        close() {
            this.$emit('close')
        },
        onVisibilitySelect(visibility) {
            this.form.visibility = visibility;
        },
        selectNewFavicon() {
            this.$refs.favicon.click();
        },
        updateFaviconPreview() {
            this.form.delete_favicon = false;

            const favicon = this.$refs.favicon.files[0];
            if (!favicon) return;

            this.form.favicon = this.$refs.favicon.files[0];

            const reader = new FileReader();

            reader.onload = (e) => {
                this.faviconPreview = e.target.result;
            }
            reader.readAsDataURL(favicon);
        },
    },
    computed: {
        canEdit() {
            return this.$page.props.teams_permissions[this.$page.props.user.current_team.id].includes('links:update')
                || this.isCurrentTeamOwner;
        },
        canDelete() {
            return this.$page.props.teams_permissions[this.$page.props.user.current_team.id].includes('links:delete')
                || this.isCurrentTeamOwner;
        },
        isCurrentTeamOwner() {
            return this.$page.props.user.owned_teams.map(t => t.id).includes(this.$page.props.user.current_team.id)
        },
        visibility() {
            if (this.form.visibility) return "Public"
            return "Private"
        }
    },
}
</script>

<style scoped>

</style>

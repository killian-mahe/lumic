<template>
    <jet-dialog-modal @close="close">

        <template #title>
            Edit link
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
            </div>
        </template>

        <template #footer>
            <jet-danger-button class="mr-3" v-if="canDelete" :disabled="modelValue.processing" @click="$emit('delete')">Delete</jet-danger-button>
            <jet-button :disabled="modelValue.processing" v-if="canEdit" @click="$emit('save')">Save</jet-button>
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

export default {
    name: "EditLinkModal",
    emits: ['close', 'delete', 'save', 'update:modelValue'],
    components: {
        JetDialogModal,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
        JetDangerButton
    },
    props: ['modelValue'],
    methods: {
        close() {
            this.$emit('close')
        },
    },
    computed: {
        form: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
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
        }
    }
}
</script>

<style scoped>

</style>

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
                </div>

                <div>
                    <jet-label for="link_value">Value</jet-label>
                    <jet-input type="text" id="link_value" class="mt-1 block w-full" v-model="form.value"></jet-input>
                </div>
            </div>
        </template>

        <template #footer>
            <jet-button :disabled="form.processing" @click="newLink">Create</jet-button>
        </template>

    </jet-dialog-modal>
</template>

<script>
import JetDialogModal from '@/Jetstream/DialogModal'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from '@/Jetstream/DangerButton'

export default {
    name: "NewLinkModal",
    emits: ['close'],
    components: {
        JetDialogModal,
        JetInput,
        JetLabel,
        JetButton,
        JetDangerButton
    },
    data() {
        return {
            form : this.$inertia.form({
                name: "",
                value: ""
            })
        }
    },
    methods: {
        close() {
            this.$emit('close')
        },
        newLink() {
            console.log("Sending form to " + route('link.store'))
            this.form.post(route('link.store'), {
                onSuccess: () => this.close()
            });
        }
    }
}
</script>

<style scoped>

</style>

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
                </div>

                <div>
                    <jet-label for="link_value">Value</jet-label>
                    <jet-input type="text" id="link_value" class="mt-1 block w-full" v-model="form.value"></jet-input>
                </div>
            </div>
        </template>

        <template #footer>
            <jet-danger-button class="mr-3" :disabled="modelValue.processing" @click="$emit('delete')">Delete</jet-danger-button>
            <jet-button :disabled="modelValue.processing" @click="$emit('save')">Save</jet-button>
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
    name: "EditLinkModal",
    emits: ['close', 'delete', 'save', 'update:modelValue'],
    components: {
        JetDialogModal,
        JetInput,
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
        }
    }
}
</script>

<style scoped>

</style>

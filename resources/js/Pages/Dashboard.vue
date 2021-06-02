<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <jet-dialog-modal :show="EditingLink" @close="EditingLink = false">

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
                <jet-danger-button class="mr-3" :disabled="form.processing" @click="deleteLink">Delete</jet-danger-button>
                <jet-button :disabled="form.processing" @click="updateLink">Save</jet-button>
            </template>

        </jet-dialog-modal>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-2">

                    <div v-for="link in links" :key="'link_'+link.id" class="p-4 flex flex-col bg-white shadow-lg sm:rounded-lg">

                        <span class="font-semibold text-xl text-center">{{link.name}}</span>

                        <a :href="link.value" class="mt-8 truncate text-gray-500 text-center hover:underline">{{link.value}}</a>

                        <div class="flex justify-around mt-8">
                            <jet-button type="button" @click="edit(link)">Edit</jet-button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import JetButton from '@/Jetstream/Button'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetInput from '@/Jetstream/Input'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'

    export default {
        components: {
            JetApplicationLogo,
            AppLayout,
            JetButton,
            JetDangerButton,
            Welcome,
            JetDialogModal,
            JetInput,
            JetActionMessage,
            JetLabel
        },
        props: {
            links: Array
        },
        data() {
            return {
                EditingLink: false,
                form: this.$inertia.form({
                    name: '',
                    value: '',
                    id: 0
                })
            }
        },
        methods: {
            updateLink() {
                this.form.put(route('link.update', this.form.id), {
                    onSuccess: () => this.EditingLink = false
                })
            },
            deleteLink() {
                this.form.delete(route('link.destroy', this.form.id), {
                    onSuccess: () => this.EditingLink = false
                })
            },
            edit(link) {
                this.form.name = link.name;
                this.form.value = link.value;
                this.form.id = link.id;
                this.EditingLink = true;
            }
        }
    }
</script>

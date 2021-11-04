<template>
    <div v-if="!hasAcceptedCookies"
        class="fixed bottom-0 inset-x-0 bg-white shadow-lg py-5 px-6 flex justify-center space-x-4 items-center">
        <span>Do you like cookies ? We use cookies to ensure you get the best experience on our website.</span>
        <jetstream-button @click="acceptCookies">I agree</jetstream-button>
    </div>
</template>

<script>
import JetstreamButton from '@/Jetstream/Button'

export default {
    name: "CoookieConsentBanner",
    components: {
      JetstreamButton
    },
    data() {
        return {
            hasAcceptedCookies: !!this.getCookie("acceptCookies")
        }
    },
    methods: {
        acceptCookies() {
            this.setCookie("acceptCookies", true, 30);
            this.hasAcceptedCookies = !!this.getCookie("acceptCookies");
        },
        setCookie(cname, cvalue, exdays) {
            let d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        },
        getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    }
}
</script>

<style scoped>

</style>

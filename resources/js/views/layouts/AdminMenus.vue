<template>
    <a-button
        type="link"
        @click="
            () => {
                $router.push({ name: 'admin.dashboard' });
            }
        "
    >
        Dahboard
    </a-button>
    <a-button
        v-if="authStore.loggedUser?.roles === 'admin'"
        type="link"
        @click="
            () => {
                $router.push({ name: 'admin.team_members' });
            }
        "
    >
        Team Members
    </a-button>
</template>

<script>
import { useAuthStore } from "@/stores/authStore";

export default {
    setup() {
        const authStore = useAuthStore();

        return {
            authStore,
        };
    },
};
</script>

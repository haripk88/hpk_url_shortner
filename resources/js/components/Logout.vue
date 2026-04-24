<template>
    <a-button type="link" @click="logout">Logout</a-button>
</template>

<script>
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { createVNode } from "vue";
import { useRouter } from "vue-router";
import { Modal } from "ant-design-vue";
import { useAuthStore } from "@/stores/authStore";

export default {
    setup() {
        const router = useRouter();
        const authStore = useAuthStore();

        const logout = () => {
            Modal.confirm({
                title: "Logout?",
                icon: createVNode(ExclamationCircleOutlined),
                content: "Are you sure you want to logout?",
                onOk() {
                    authStore.logout();
                },
                onCancel() {},
            });
        };

        return {
            authStore,
            logout,
        };
    },
};
</script>

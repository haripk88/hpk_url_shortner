<template>
    <a-button type="primary" @click="showModal">Invite</a-button>
    <a-modal
        v-model:open="open"
        title="Invite New Client"
        :footer="null"
        :bodyStyle="{ marginTop: '40px' }"
    >
        <a-row>
            <a-col :span="24">
                <a-form layout="vertical">
                    <a-form-item
                        label="Name"
                        name="name"
                        :help="rules.name ? rules.name[0] : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.name"
                            placeholder="Client Name"
                            @pressEnter="onSubmit"
                        />
                    </a-form-item>

                    <a-form-item
                        label="Email"
                        name="email"
                        :help="rules.email ? rules.email[0] : null"
                        :validateStatus="rules.email ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.email"
                            placeholder="Enter Client Email"
                            @pressEnter="onSubmit"
                        />
                    </a-form-item>

                    <a-form-item>
                        <a-button
                            @click="onSubmit"
                            :loading="loading"
                            type="primary"
                            block
                        >
                            Send Invitation
                        </a-button>
                    </a-form-item>
                </a-form>
            </a-col>
        </a-row>
    </a-modal>
</template>

<script>
import { ref } from "vue";
import { message } from "ant-design-vue";
import apiAdmin from "@/services/apiAdmin";

export default {
    setup() {
        const { loading, rules, apiRequest } = apiAdmin();

        const formData = ref({
            name: "",
            email: "",
        });
        const open = ref(false);

        const showModal = () => {
            open.value = true;
        };

        const onSubmit = () => {
            rules.value = {};
            loading.value = true;

            apiRequest({
                url: "superadmin/clients/invite",
                data: formData.value,
                success: (res) => {
                    open.value = false;
                    formData.value = {
                        name: "",
                        email: "",
                    };

                    message.success("Invitation send successfully.");
                },
            });
        };
        return {
            loading,
            rules,
            formData,
            showModal,
            onSubmit,
            open,
        };
    },
};
</script>

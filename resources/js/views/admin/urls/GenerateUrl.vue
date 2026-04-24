<template>
    <a-button type="primary" @click="showModal">Generate</a-button>
    <a-modal
        v-model:open="open"
        title="Generate Short URL"
        :footer="null"
        :bodyStyle="{ marginTop: '40px' }"
    >
        <a-row>
            <a-col :span="24">
                <a-form layout="vertical">
                    <a-form-item
                        label="Long URL"
                        name="url"
                        :help="rules.url ? rules.url[0] : null"
                        :validateStatus="rules.url ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.url"
                            placeholder="Enter long URL to generate short url"
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
                            Generate
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
    setup(props, { emit }) {
        const { loading, rules, apiRequest } = apiAdmin();

        const formData = ref({
            url: "",
        });
        const open = ref(false);

        const showModal = () => {
            open.value = true;
        };

        const onSubmit = () => {
            rules.value = {};
            loading.value = true;

            apiRequest({
                url: "admin/urls/generate",
                data: formData.value,
                success: (res) => {
                    open.value = false;
                    formData.value = {
                        url: "",
                    };

                    emit("success");

                    message.success(res.message);
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

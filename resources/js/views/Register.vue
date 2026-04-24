<template>
    <div class="register-container">
        <a-row justify="center" align="middle" class="main-row">
            <a-col :xs="22" :sm="20" :md="16" :lg="12" :xl="10">
                <a-skeleton v-if="pageLoading" active />
                <template v-else>
                    <a-card v-if="invitationMessage !== ''">
                        <a-result
                            :status="invitationMessageType"
                            :title="invitationMessage"
                        >
                            <template #extra>
                                <a-button
                                    type="primary"
                                    @click="
                                        () => {
                                            $router.push({ name: 'login' });
                                        }
                                    "
                                    block
                                >
                                    Login
                                </a-button>
                            </template>
                        </a-result>
                    </a-card>
                    <a-card v-else>
                        <h2 style="text-align: center; margin-bottom: 8px">
                            Complete Registration
                        </h2>
                        <p
                            style="
                                text-align: center;
                                margin-bottom: 24px;
                                color: #8c8c8c;
                            "
                        >
                            You've been invited to join {{ company.name }}
                        </p>

                        <a-form layout="vertical">
                            <a-form-item
                                label="Your Name"
                                name="name"
                                :help="rules.name ? rules.name[0] : null"
                                :validateStatus="rules.name ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.name"
                                    placeholder="Enter your name"
                                    disabled
                                />
                            </a-form-item>

                            <a-form-item
                                label="Your Email"
                                name="email"
                                :help="rules.email ? rules.email[0] : null"
                                :validateStatus="rules.email ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.email"
                                    @pressEnter="onSubmit"
                                    disabled
                                />
                            </a-form-item>

                            <a-form-item
                                v-if="company.is_supper"
                                label="Company Name"
                                name="company_name"
                                :help="rules.company_name ? rules.company_name[0] : null"
                                :validateStatus="rules.company_name ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.company_name"
                                    placeholder="Enter your company name"
                                    @pressEnter="onSubmit"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Password"
                                name="password"
                                :help="rules.password ? rules.password[0] : null"
                                :validateStatus="rules.password ? 'error' : null"
                                class="required"
                            >
                                <a-input-password
                                    v-model:value="formData.password"
                                    placeholder="Enter your password"
                                    @pressEnter="onSubmit"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Confirm Password"
                                name="password_confirmation"
                                :help="
                                    rules.password_confirmation
                                        ? rules.password_confirmation[0]
                                        : null
                                "
                                :validateStatus="
                                    rules.password_confirmation ? 'error' : null
                                "
                                class="required"
                            >
                                <a-input-password
                                    v-model:value="formData.password_confirmation"
                                    placeholder="Confirm your password"
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
                                    Complete Registration
                                </a-button>
                            </a-form-item>

                            <div style="text-align: center">
                                <a-button
                                    type="link"
                                    @click="$router.push({ name: 'login' })"
                                    block
                                >
                                    Already have an account? Login
                                </a-button>
                            </div>
                        </a-form>
                    </a-card>
                </template>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import apiAdmin from "@/services/apiAdmin";

export default {
    setup() {
        const route = useRoute();
        const router = useRouter();
        const { loading, rules, apiRequest } = apiAdmin();
        const pageLoading = ref(true);

        const formData = ref({
            email: "",
            name: "",
            company_name: "",
            password: "",
            password_confirmation: "",
        });

        const inviteCode = route.params.id;

        const company = ref({});
        const invitationMessageType = ref("warning");
        const invitationMessage = ref("");

        onMounted(() => {
            pageLoading.value = true;

            if (inviteCode) {
                apiRequest({
                    url: `invitation/${inviteCode}`,
                    data: {},
                    success: (res) => {
                        const response = res.data.data;
                        company.value = response.company;

                        formData.value = {
                            ...formData.value,
                            name: response.name,
                            email: response.email,
                        };

                        pageLoading.value = false;
                    },
                    error: (err) => {
                        invitationMessage.value = err.data.message;
                        pageLoading.value = false;
                    },
                });
            } else {
                invitationMessage.value = "Invitation code not exists.";
                pageLoading.value = false;
                return;
            }
        });

        const onSubmit = () => {
            rules.value = {};
            loading.value = true;

            apiRequest({
                url: `invitation/${inviteCode}/register`,
                data: formData.value,
                success: (res) => {
                    invitationMessageType.value = "success";
                    invitationMessage.value = res.message;
                },
            });
        };
        return {
            onSubmit,
            pageLoading,
            loading,
            rules,
            formData,
            invitationMessageType,
            invitationMessage,
            company,
        };
    },
};
</script>

<style scoped>
.invite-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.main-row {
    height: 100%;
    width: 50%;
}
</style>

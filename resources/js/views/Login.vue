<template>
    <div class="login-page">
        <section class="login-panel">
            <div class="login-panel__content">
                <a-card class="login-card" :bordered="false">
                    <h2>Login</h2>
                    <a-form layout="vertical">
                        <a-form-item
                            label="Email"
                            name="email"
                            :help="rules.email ? rules.email[0] : null"
                            :validateStatus="rules.email ? 'error' : null"
                            class="required"
                        >
                            <a-input
                                v-model:value="formData.email"
                                placeholder="Please Enter Your Email"
                                @pressEnter="onSubmit"
                                size="large"
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
                                placeholder="Please Enter Your Password"
                                @pressEnter="onSubmit"
                                size="large"
                            />
                        </a-form-item>

                        <a-form-item>
                            <a-button
                                @click="onSubmit"
                                :loading="loading"
                                type="primary"
                                block
                                size="large"
                                class="submit-btn"
                            >
                                Submit
                            </a-button>
                        </a-form-item>
                    </a-form>
                </a-card>
            </div>
        </section>

        <section class="art-panel" aria-hidden="true">
            <div class="wash wash-one"></div>
            <div class="wash wash-two"></div>
            <div class="wash wash-three"></div>
            <div class="art-grid"></div>
        </section>
    </div>
</template>
<script>
import { ref } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useRouter } from "vue-router";
import apiAdmin from "@/services/apiAdmin";

export default {
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();
        const { loading, rules, apiRequest } = apiAdmin();
        const formData = ref({
            email: "",
            password: "",
        });

        const onSubmit = () => {
            rules.value = {};
            loading.value = true;

            apiRequest({
                url: "login",
                data: formData.value,
                success: (response) => {
                    const user = response.data.user;
                    // console.log("user", user);
                    authStore.updateAuthToken(response.data.token);
                    authStore.updateUser(user);
                    // router.push({ name: "/dashboad" });
                    const redirectUrl =
                        user.roles === "superadmin"
                            ? "superadmin.dashboard"
                            : "admin.dashboard";
                    router.push({ name: redirectUrl });
                },
            });
        };

        return {
            formData,
            onSubmit,
            rules,
            loading,
        };
    },
};
</script>
<style scoped>
.login-page {
    --panel-bg: #f9fbff;
    --panel-border: rgba(125, 166, 214, 0.18);
    --text-main: #183153;
    --text-soft: #5c7192;
    --brand: #2b6cb0;
    --brand-deep: #174a7c;
    --wash-1: rgba(163, 214, 255, 0.5);
    --wash-2: rgba(114, 187, 255, 0.28);
    --wash-3: rgba(214, 238, 255, 0.85);
    min-height: 100vh;
    display: grid;
    grid-template-columns: minmax(320px, 34%) 1fr;
    background: linear-gradient(135deg, #eef5ff 0%, #f8fbff 100%);
}

.login-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 28px;
    background: radial-gradient(
            circle at top left,
            rgba(121, 175, 236, 0.12),
            transparent 38%
        ),
        linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 249, 255, 0.98));
    border-right: 1px solid rgba(125, 166, 214, 0.12);
    box-shadow: 16px 0 42px rgba(22, 58, 101, 0.08);
    position: relative;
    z-index: 1;
}

.login-panel__content {
    width: min(100%, 390px);
}

.login-copy {
    margin-bottom: 26px;
}

.eyebrow {
    margin: 0 0 8px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--brand);
}

.login-copy h1 {
    margin: 0;
    font-size: clamp(2rem, 4vw, 2.8rem);
    line-height: 1.05;
    color: var(--text-main);
}

.subtitle {
    margin: 14px 0 0;
    font-size: 1rem;
    line-height: 1.7;
    color: var(--text-soft);
}

.login-card {
    border-radius: 26px;
    background: rgba(255, 255, 255, 0.82);
    box-shadow: 0 26px 54px rgba(61, 109, 161, 0.16);
    border: 1px solid var(--panel-border);
    backdrop-filter: blur(14px);
}

.login-card :deep(.ant-card-body) {
    padding: 30px 28px 20px;
}

.login-card h2 {
    margin: 0 0 22px;
    text-align: center;
    color: var(--text-main);
    font-size: 1.6rem;
}

.submit-btn {
    margin-top: 6px;
    height: 46px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
    box-shadow: 0 14px 28px rgba(43, 108, 176, 0.28);
}

.art-panel {
    position: relative;
    overflow: hidden;
    background: radial-gradient(
            circle at 18% 20%,
            rgba(255, 255, 255, 0.78),
            transparent 22%
        ),
        radial-gradient(circle at 70% 28%, rgba(255, 255, 255, 0.5), transparent 18%),
        linear-gradient(145deg, #dff1ff 0%, #bfe4ff 40%, #d4edff 100%);
}

.wash {
    position: absolute;
    border-radius: 50%;
    filter: blur(12px);
    opacity: 0.9;
}

.wash-one {
    width: 420px;
    height: 420px;
    top: -80px;
    right: 10%;
    background: var(--wash-1);
}

.wash-two {
    width: 520px;
    height: 520px;
    bottom: -180px;
    left: 8%;
    background: var(--wash-2);
}

.wash-three {
    width: 280px;
    height: 280px;
    top: 34%;
    right: 24%;
    background: var(--wash-3);
}

.art-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255, 255, 255, 0.18) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.18) 1px, transparent 1px);
    background-size: 34px 34px;
    mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.8), transparent 78%);
}

.art-copy {
    position: absolute;
    left: 10%;
    bottom: 10%;
    padding: 24px 28px;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.34);
    border: 1px solid rgba(255, 255, 255, 0.42);
    color: #18486f;
    backdrop-filter: blur(10px);
    box-shadow: 0 18px 40px rgba(48, 106, 156, 0.12);
}

.art-copy span {
    display: block;
    margin-bottom: 8px;
    font-size: 0.82rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    font-weight: 700;
}

.art-copy p {
    margin: 0;
    max-width: 260px;
    font-size: 1.05rem;
    line-height: 1.6;
}

@media (max-width: 980px) {
    .login-page {
        grid-template-columns: 1fr;
    }

    .login-panel {
        min-height: 100vh;
        border-right: none;
        box-shadow: none;
    }

    .art-panel {
        min-height: 280px;
    }
}

@media (max-width: 640px) {
    .login-panel {
        padding: 22px 16px;
    }

    .login-card :deep(.ant-card-body) {
        padding: 24px 18px 16px;
    }

    .art-copy {
        left: 16px;
        right: 16px;
        bottom: 16px;
        padding: 18px 20px;
    }
}
</style>

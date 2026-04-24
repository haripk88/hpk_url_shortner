import { createWebHistory, createRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import superadminRoute from "@/router/superadmin";
import adminRoute from "@/router/admin";

const routes = [
  {
    path: "/invite/:id",
    name: "invite.register",
    component: () => import("@/views/Register.vue"),
    meta: {
      requireUnauth: true,
    },
  },
  {
    path: "/",
    name: "login",
    component: () => import("@/views/Login.vue"),
    meta: {
      requireUnauth: true,
    },
  },
  ...adminRoute,
  ...superadminRoute,
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta && to.meta.requireAuth && !authStore.isLoggedIn) {
    return next({ name: "login" });
  } else if (to.name === "login" && authStore.isLoggedIn) {
    const redirectUrl =
      authStore.user?.roles === "superadmin"
        ? "superadmin.dashboard"
        : "admin.dashboard";
    return next({ name: redirectUrl });
  } else if (to.meta?.allowedUserType && authStore.user) {
    const allowedTypes = to.meta.allowedUserType;

    if (!allowedTypes.includes(authStore.user.roles)) {
      const redirectUrl =
        authStore.user.roles === "superadmin"
          ? "superadmin.dashboard"
          : "admin.dashboard";
      return next({ name: redirectUrl });
    }
  }

  next();
});

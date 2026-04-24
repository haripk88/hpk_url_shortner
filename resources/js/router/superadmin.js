const routes = [{
    path: "/",
    component: () => import("@/views/layouts/Layout.vue"),
    children: [
        {
            path: "/superadmin/dashboard",
            name: "superadmin.dashboard",
            component: () => import("@/views/superadmin/Dashboard.vue"),
            meta: {
                requireAuth: true,
                allowedUserType: ['superadmin']
            }
        },
        {
            path: "/superadmin/urls/:client_id",
            name: "superadmin.urls.client",
            component: () => import("@/views/superadmin/urls/index.vue"),
            meta: {
                requireAuth: true,
                allowedUserType: ['superadmin']
            }
        },
        {
            path: "/superadmin/urls",
            name: "superadmin.urls",
            component: () => import("@/views/superadmin/urls/index.vue"),
            meta: {
                requireAuth: true,
                allowedUserType: ['superadmin']
            }
        }
    ]
}];

export default routes;

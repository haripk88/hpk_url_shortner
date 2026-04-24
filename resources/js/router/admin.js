const routes = [{
    path: "/",
    component: () => import("@/views/layouts/Layout.vue"),
    children: [
        {
            path: "/admin/dashboard",
            name: "admin.dashboard",
            component: () => import("@/views/admin/Dashboard.vue"),
            meta: {
                requireAuth: true,
                allowedUserType: ['admin', 'member']
            }
        },
        {
            path: "/admin/team-members",
            name: "admin.team_members",
            component: () => import("@/views/admin/team-members/index.vue"),
            meta: {
                requireAuth: true,
                allowedUserType: ['admin']
            }
        }
    ]
}];

export default routes;

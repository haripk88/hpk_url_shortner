<template>
    <a-button
        type="link"
        @click="
            () => {
                $router.push({ name: 'superadmin.dashboard' });
            }
        "
    >
        Dahboard
    </a-button>
    <a-button
        type="link"
        @click="
            () => {
                $router.push({ name: 'superadmin.urls' });
            }
        "
    >
        Short URLs
    </a-button>
</template>

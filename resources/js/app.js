import "./global";
// import '../css/app.css'
import { createApp } from "vue";
import App from "@/views/App.vue";
import { router } from "@/router/index.js";
import { createPinia } from "pinia";
import { useAuthStore } from "@/stores/authStore";

const bootstarp = async () => {
  const app = createApp(App);
  app.use(createPinia());

  const authStore = useAuthStore();
  await authStore.updateUserAction();

  app.use(router);
  app.mount("#app");
};

bootstarp();

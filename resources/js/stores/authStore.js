import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { router } from "../router/index";
const AUTH_USER = "auth_user";
const AUTH_TOKEN = "auth_token";

const getLocalStorage = (newVal) => {
  return window.localStorage.getItem(newVal);
};

export const useAuthStore = defineStore("auth", () => {
  const loggedUser = ref({});
  const authToken = ref(getLocalStorage(AUTH_TOKEN));
  const isLoggedIn = computed(() =>
    authToken.value && authToken.value != "" ? true : false,
  );

  const updateUser = (newVal) => {
    loggedUser.value = newVal;
    console.log("user", loggedUser.value);
  };

  const updateAuthToken = (newVal) => {
    authToken.value = newVal;
    console.log("token", newVal);
    window.localStorage.setItem(AUTH_TOKEN, newVal);
  };

  const updateUserAction = async () => {
    if (authToken.value) {
      const axiosHead = window.axiosHead;

      const response = await axiosHead.post("/user");
      console.log(response.data.user);
      updateUser(response.data.user);
    }
  };

  const logout = () => {
    if (authToken.value) {
      const axiosHead = window.axiosHead;

      axiosHead
        .post("/logout")
        .then(function (response) {
          window.localStorage.clear();

          updateAuthToken(null);
          updateUser(null);

          router.push({ name: "login" });
        })
        .catch(function (error) {});
    }
  };

  return {
    loggedUser,
    authToken,
    isLoggedIn,
    updateUser,
    updateAuthToken,
    updateUserAction,
    logout,
  };
});

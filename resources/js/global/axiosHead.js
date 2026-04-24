import axios from "axios";
import { message } from "ant-design-vue";
const AUTH_TOKEN = "auth_token";

var axiosHead = axios.create({
  baseURL: window.config.base_url + "/api",
});

axiosHead.defaults.headers["Accept"] = "application/json";
axiosHead.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axiosHead.interceptors.request.use((config) => {
  const authToken = window.localStorage.getItem(AUTH_TOKEN);
  if (authToken) {
    config.headers["Authorization"] = `Bearer ${authToken}`;
  }

  return config;
});

axiosHead.interceptors.response.use(
  function (response) {
    return response.data;
  },
  function (error) {
    const errorCode = error.response.status;
    if (errorCode === 401) {
      window.localStorage.clear();

      // If error 401 redirect to login
      window.location.href = window.config.base_url;
    } else if (errorCode === 403) {
      message.error(error.response.data.message || "Forbidden");
      return Promise.reject(error.response);
    } else {
      return Promise.reject(error.response);
    }
  },
);

window.axiosHead = axiosHead;

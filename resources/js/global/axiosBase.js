import axios from "axios";

var axiosBase = axios.create({
  baseURL: window.config.base_url + "/api",
});

axiosBase.defaults.headers["Accept"] = "application/json";
axiosBase.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axiosBase.interceptors.response.use(
  function (response) {
    return response.data;
  },
  function (error) {
    const errorCode = error.response.status;
    if (errorCode === 401) {
      // If error 401 redirect to login
      window.location.href = window.config.base_url;
    } else {
      return Promise.reject(error.response);
    }
  },
);

window.axiosBase = axiosBase;

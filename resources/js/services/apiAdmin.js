import { ref } from "vue";

const apiAdmin = () => {
  const axiosHead = window.axiosHead;
  const rules = ref({});
  const loading = ref(false);

  const apiRequest = (configObject) => {
    const { url, data } = configObject;
    loading.value = true;
    rules.value = {};

    axiosHead
      .post(url, data)
      .then((res) => {
        loading.value = false;
        rules.value = {};

        if (configObject.success) {
          configObject.success(res);
        }
      })
      .catch((error) => {
        rules.value = error.data && error.data.errors ? error.data.errors : {};

        loading.value = false;

        if (configObject.error) {
          configObject.error(error);
        }
      });
  };

  return {
    rules,
    loading,
    apiRequest,
  };
};

export default apiAdmin;

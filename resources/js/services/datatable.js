import { ref } from "vue";

const datatable = () => {
  const data = ref([]);
  const pageSize = ref(10);
  const currentPage = ref(1);
  const tablePagination = ref({
    pageSize: pageSize.value,
    showSizeChanger: true,
  });

  const url = ref("");
  const params = ref({});

  const fetchData = () => {
    axiosHead
      .post(url.value, {
        per_page: pageSize.value,
        page: currentPage.value,
        ...params.value,
      })
      .then((res) => {
        data.value = res.data.data;
        tablePagination.value = {
          ...tablePagination.value,
          total: res.data.meta.total,
        };
      });
  };

  const handleTableChange = (pagination, filters, sorter) => {
    pageSize.value = pagination.pageSize;
    currentPage.value = pagination.current;

    tablePagination.value = {
      ...pagination,
    };

    fetchData();
  };

  return {
    url,
    params,
    data,
    tablePagination,

    fetchData,
    handleTableChange,
  };
};

export default datatable;

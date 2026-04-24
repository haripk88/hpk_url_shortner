import { ref } from "vue";
import dayjs from "dayjs";

const fields = () => {
  const columns = [
    {
      title: "Short URL",
      dataIndex: "short_url",
    },
    {
      title: "Long URL",
      dataIndex: "url",
    },
    {
      title: "Hits",
      dataIndex: "hits",
    },
    {
      title: "Client",
      dataIndex: "client",
    },
    {
      title: "Created On",
      dataIndex: "created_at",
    },
  ];

  const rangePresets = ref([
    {
      label: "Last 7 Days",
      value: [dayjs().add(-7, "d"), dayjs()],
    },
    {
      label: "Last 14 Days",
      value: [dayjs().add(-14, "d"), dayjs()],
    },
    {
      label: "Last 30 Days",
      value: [dayjs().add(-30, "d"), dayjs()],
    },
    {
      label: "Last 90 Days",
      value: [dayjs().add(-90, "d"), dayjs()],
    },
  ]);

  return {
    columns,
    rangePresets,
  };
};

export default fields;

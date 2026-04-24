<template>
    <a-page-header style="padding: 25px 0px" title="Short URLs">
        <template #extra>
            <a-range-picker :presets="rangePresets" @change="onRangeChange" />
            <a-button type="primary" @click="download" :loading="downloading">
                Download
            </a-button>
        </template>
    </a-page-header>

    <a-table
        :columns="columns"
        :data-source="data"
        :pagination="tablePagination"
        @change="handleTableChange"
        size="middle"
    >
        <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex === 'created_at'">
                {{ dayjs(record.created_at).format("DD MMM YYYY") }}
            </template>
            <template v-if="column.dataIndex === 'short_url'">
                <a-typography-link :href="record.short_url" target="_blank">
                    {{ record.short_url }}
                </a-typography-link>
            </template>
            <template v-if="column.dataIndex === 'client'">
                <a-button
                    type="link"
                    @click="
                        () => {
                            $router.push({
                                name: 'superadmin.urls.client',
                                params: { client_id: record.client_id },
                            });
                        }
                    "
                    style="padding: 0"
                >
                    {{ record.client }}
                </a-button>
            </template>
        </template>
    </a-table>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import dayjs from "dayjs";
import { useRoute } from "vue-router";
import fields from "./fields";
import datatable from "@/services/datatable";

export default {
    setup() {
        const route = useRoute();
        const downloading = ref(false);
        const { columns, rangePresets } = fields();
        const {
            url,
            data,
            params,
            tablePagination,
            fetchData,
            handleTableChange,
        } = datatable();

        const loadData = () => {
            const clientId = route.params.client_id;

            if (clientId) {
                params.value.company_id = clientId;
            } else {
                delete params.value.company_id;
            }

            url.value = "superadmin/urls";
            fetchData();
        };

        onMounted(() => {
            loadData();
        });

        const onRangeChange = (dates, dateStrings) => {
            if (dates) {
                params.value.start_date = dateStrings[0];
                params.value.end_date = dateStrings[1];
            } else {
                delete params.value.start_date;
                delete params.value.end_date;
            }
            fetchData();
        };

        const download = () => {
            downloading.value = true;

            const docType = "text/csv";
            const payload = { ...params.value };
            const fileName = "urls.csv";

            axiosFile
                .post("superadmin/urls/download", payload, {
                    responseType: "blob",
                })
                .then((response) => {
                    // Create a blob from the response data
                    const blob = new Blob([response.data], {
                        type: docType,
                    });

                    // Create a temporary URL for the blob
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement("a");
                    link.href = url;

                    // Set the link attributes to trigger download
                    link.setAttribute("download", fileName);
                    document.body.appendChild(link);
                    link.click();

                    // Clean up by removing the temporary link and URL object
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);

                    downloading.value = false;
                })
                .catch((error) => {
                    downloading.value = false;
                });
        };

        // Watch for route param changes
        watch(
            () => route.params.client_id,
            (newClientId, oldClientId) => {
                if (newClientId !== oldClientId) {
                    loadData();
                }
            }
        );

        return {
            download,
            onRangeChange,
            route,
            downloading,
            columns,
            rangePresets,
            data,
            tablePagination,
            handleTableChange,
            dayjs,
        };
    },
};
</script>

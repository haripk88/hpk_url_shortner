const fields = () => {
    const columns = [
        {
            title: "Client name",
            dataIndex: "name",
        },
        {
            title: "Users",
            dataIndex: "total_users",
        },
        {
            title: "Total Generated URLs",
            dataIndex: "total_urls",
        },
        {
            title: "Total URL Hits",
            dataIndex: "total_url_hits",
        },
    ];

    return {
        columns
    }
}


export default fields;

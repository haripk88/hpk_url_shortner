const fields = () => {
    const columns = [
        {
            title: "Name",
            dataIndex: "name",
        },
        {
            title: "Email",
            dataIndex: "email",
        },
        {
            title: "Role",
            dataIndex: "role",
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

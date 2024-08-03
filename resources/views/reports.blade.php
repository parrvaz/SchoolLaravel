<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Power BI Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/powerbi-client/2.9.1/powerbi.min.js"></script>
</head>
<body>
<div id="reportContainer" style="height: 800px;"></div>

<script>
    async function embedReport() {
        const response = await fetch('/get-access-token');
        const token = await response.json();

        const embedConfig = {
            type: 'report',
            tokenType: 1,
            accessToken: token,
            embedUrl: `https://app.powerbi.com/reportEmbed?reportId={{ env('POWERBI_REPORT_ID') }}&groupId={{ env('POWERBI_WORKSPACE_ID') }}`,
            id: '{{ env('POWERBI_REPORT_ID') }}',
            filters: [
                {
                    $schema: "http://powerbi.com/product/schema#basic",
                    target: {
                        table: "YourTableName",
                        column: "YourColumnName"
                    },
                    operator: "In",
                    values: ["FilterValue"]
                }
            ],
            settings: {
                filterPaneEnabled: false,
                navContentPaneEnabled: false
            }
        };

        const reportContainer = document.getElementById('reportContainer');
        powerbi.embed(reportContainer, embedConfig);
    }

    embedReport();
</script>
</body>
</html>

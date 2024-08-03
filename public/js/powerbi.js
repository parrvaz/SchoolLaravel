import * as pbi from 'powerbi-client';

async function embedReport() {
    const response = await fetch('/get-access-token');
    const token = await response.json();

    const embedConfig = {
        type: 'report',
        tokenType: 1,
        accessToken: token,
        embedUrl: `https://app.powerbi.com/reportEmbed?reportId=${process.env.MIX_POWERBI_REPORT_ID}&groupId=${process.env.MIX_POWERBI_WORKSPACE_ID}`,
        id: process.env.MIX_POWERBI_REPORT_ID,
        settings: {
            filterPaneEnabled: false,
            navContentPaneEnabled: false
        }
    };

    const reportContainer = document.getElementById('reportContainer');
    pbi.embed(reportContainer, embedConfig);
}

window.addEventListener('load', embedReport);

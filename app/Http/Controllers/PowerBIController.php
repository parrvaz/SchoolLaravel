<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PowerBIController extends Controller
{

    public function show(Request $request){
        $url = "https://app.powerbi.com/view?r=eyJrIjoiYTE3YTZmN2YtNTJjNy00ZWRlLTg5Y2ItMmM5ZGY4ODMyZDBmIiwidCI6ImEwNTdmZDk5LWQwYjMtNGEzYy05MDc5LWY3ZmI3YzZkNjRiNyJ9";

        $filter = $request->query('filter', "Classes/number eq '101'");
        return view('powerbi', compact('filter'));
    }

    public function index()
    {
        // تنظیمات دسترسی به Power BI API
        $workspaceId = 'me';
        $reportId = env('POWERBI_REPORT_ID');
        $accessToken = $this->getAccessToken();

        return view('powerbi', compact('workspaceId', 'reportId', 'accessToken'));
    }


//    public function show(){
//        $client = new Client();
//        $accessToken = $this->getAccessToken();
//        $groupId = 'me';
//        $reportId = env("POWERBI_REPORT_ID");
//
//
//        /*
//         * https://app.powerbi.com/groups/me/reports/a25c81db-1b57-46d5-a164-371497d014ea/a958d5ed58011ed2a90c?experience=power-bi
//         */
//
//        $response = $client->post("https://api.powerbi.com/v1.0/myorg/groups/{$groupId}/reports/{$reportId}/GenerateToken", [
//            'headers' => [
//                'Authorization' => "Bearer {$accessToken}",
//                'Content-Type' => 'application/json',
//            ],
//            'json' => [
//                'accessLevel' => 'View',
//                'identities' => [
//                    [
//                        'username' => 'user@example.com',
//                        'roles' => ['Viewer'],
//                        'datasets' => ['your_dataset_id'],
//                    ],
//                ],
//            ],
//        ]);
//
//        $embedToken = json_decode($response->getBody(), true)['token'];
//        return $embedToken;
//    }



    public function getAccessToken()
    {
        $client = new Client();
        $response = $client->post('https://login.microsoftonline.com/' . env('POWERBI_TENANT_ID') . '/oauth2/v2.0/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('POWERBI_CLIENT_ID'),
                'client_secret' => env('POWERBI_CLIENT_SECRET'),
                'scope' => 'https://analysis.windows.net/powerbi/api/.default'
            ]
        ]);

        $token = json_decode((string) $response->getBody(), true);
        return $token['access_token'];
    }

    public function viewReport(){

// تنظیمات اولیه
        $clientId = 'f1b07b1d-583c-446e-82e6-2a29f9e89bd8';
        $clientSecret = 'HOh8Q~p84AhZ7BgkZCEfqwck7FR8y2mVO8lsMcdy';
        $reportId = 'a25c81db-1b57-46d5-a164-371497d014ea/a958d5ed58011ed2a90c';

// ایجاد یک مشتری HTTP
        $client = new Client();

        $accessToken = $this->getAccessToken();

// ساخت درخواست برای دریافت داده‌های فیلتر شده
//        $response = $client->get('https://api.powerbi.com/v1.0/myorg/groups/me/reports/' . $reportId . '/data', [
//            'headers' => [
//                'Authorization' => 'Bearer ' . $accessToken
//            ],
//            'query' => [
////                '$filter' => "ColumnName eq 'value'" // مثال یک فیلتر ساده
//            ]
//        ]);



        $response = $client->get('https://app.powerbi.com/reportEmbed?reportId=a25c81db-1b57-46d5-a164-371497d014ea&autoAuth=true&ctid=a057fd99-d0b3-4a3c-9079-f7fb7c6d64b7', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ],
            'query' => [
//                '$filter' => "ColumnName eq 'value'" // مثال یک فیلتر ساده
            ]
        ]);

// پردازش داده‌های دریافتی
        $data = json_decode($response->getBody(), true);

        return $data;
    }
}

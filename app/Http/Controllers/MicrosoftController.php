<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class MicrosoftController extends Controller
{
    public function redirectToProvider()
    {
        $query = http_build_query([
            'client_id' => env('MICROSOFT_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => env('MICROSOFT_REDIRECT_URI'),
            'response_mode' => 'query',
            'scope' => 'openid profile User.Read',
            'state' => csrf_token(),
        ]);

        return redirect('https://login.microsoftonline.com/' . env('MICROSOFT_TENANT_ID') . '/oauth2/v2.0/authorize?' . $query);
    }

    public function handleProviderCallback(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        $response = $http->post('https://login.microsoftonline.com/' . env('MICROSOFT_TENANT_ID') . '/oauth2/v2.0/token', [
            'form_params' => [
                'client_id' => env('MICROSOFT_CLIENT_ID'),
                'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
                'code' => $request->code,
                'redirect_uri' => env('MICROSOFT_REDIRECT_URI'),
                'grant_type' => 'authorization_code',
            ],
        ]);

        $tokens = json_decode((string) $response->getBody(), true);
        $accessToken = $tokens['access_token'];

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $user = $graph->createRequest("GET", "/me")
            ->setReturnType(Model\User::class)
            ->execute();

        // Do something with the user information
        dd($user);
    }
}

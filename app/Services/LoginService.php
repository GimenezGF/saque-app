<?php

namespace App\Services;

use App\Models\ContaCorrente;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class LoginService
{
    /**
     * Summary of login
     * @param mixed $loginData
     * @return array
     */
    public function loginBank($loginData, $uuidEmpresa)
    {
        $url = env('USUARIO_URL') . '/api/user-auth';

        try {

            $sendPost = Http::withHeaders([
                'uuidEmpresa' => $uuidEmpresa,
            ])->post($url, $loginData);

            return [
                'status' => $sendPost->status(),
                'response' => collect(json_decode($sendPost->body())),
            ];

        } catch (Exception $e) {
            return [
                'status' => 500,
                'response' => 'Falha na comunicação com o servidor externo.'
            ];
        }

    }

    public function login($loginData)
    {
        $url = env('CARTOS_BASE_URL') . '/users/v1/login';
        $apiKey = env('CARTOS_API_KEY');
        $deviceId = 'default';

        try {

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'device_id' => $deviceId,
            ])->post($url, $loginData);

            return [
                'status' => $response->status(),
                'response' => collect(json_decode($response->body())),
            ];

        } catch (Exception $e) {
            return [
                'status' => 400,
                'response' => 'Falha na comunicação com o servidor externo'
            ];
        }
    }
}

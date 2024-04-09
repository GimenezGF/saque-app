<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class AreaPixService
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function gerarQrCodeStatic($token, $pixData)
    {
        $url = env('CARTOS_BASE_URL') . '/digital-account/v1/pix-static-qrcodes';
        $apiKey = env('CARTOS_API_KEY');
        $deviceId = 'default';

        try {

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'device_id' => $deviceId,
                'Authorization' => 'Bearer ' . $token
            ])->post($url, $pixData);

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

    public function gerarQrCodeDinamic($token, $pixData)
    {

        $url = env('CARTOS_BASE_URL') . '/digital-account/v1/pix-dynamic-qrcodes';
        $apiKey = env('CARTOS_API_KEY');
        $deviceId = 'default';

        try {

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'device_id' => $deviceId,
                'Authorization' => 'Bearer ' . $token
            ])->post($url, $pixData);

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

    public function getExtrato($token, $uuidEmpresa, $accountId)
    {
        $url = 'https://transacaohub-dev.beasabank.com.br/extrato/v1/busca/15/' . $accountId;

        try {

            $response = Http::withHeaders([
                'uuidEmpresa' => $uuidEmpresa,
                'accountId' => $accountId,
                'Authorization' => $token
            ])->get($url);

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

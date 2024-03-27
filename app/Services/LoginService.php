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
    public function login($loginData, $uuidEmpresa)
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
}

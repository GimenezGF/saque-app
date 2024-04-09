<?php

namespace App\Http\Controllers;

use App\Services\AreaPixService;
use App\Services\LoginService;
use Illuminate\Http\Request;

class SaqueController extends Controller
{
    protected $loginService;

    protected $areaPixService;

    public function __construct(LoginService $loginService, AreaPixService $areaPixService)
    {
        $this->loginService = $loginService;
        $this->areaPixService = $areaPixService;
    }

    public function gerarSaque(Request $request)
    {

        $token = $this->login();

        $dataQrCode = [
            'receiverKey' => env('CONTA_PIX_KEY'),
            'merchantCity' => 'Porto Alegre',
            'value' => $request->valor,
            'txId' => substr(md5(uniqid()), 0, 25),
            'additionalInfo' => $request->mensagem ?? ""
        ];

        $responseData = collect($this->areaPixService->gerarQrCodeStatic($token, $dataQrCode));
        if ($responseData->get('status') != 200) {
            return response([
                'sucesso' => false,
                'mensagem' => 'Falha ao gerar QrCode'
            ], 400);
        }

        return response([
            'sucesso' => true,
            'mensagem' => 'QrCode gerado com sucesso',
            'data' => $responseData->get('response')->get('QRCode')->EMV
        ], 200);

    }

    public function getExtrato(Request $request)
    {

        $token = $this->login();
        $accountId = env('CONTA_ACCOUNT');
        $uuidEmpresa = env('BANCO_UUID_EMPRESA');

        $responseData = collect($this->areaPixService->getExtrato($token, $uuidEmpresa, $accountId));
        if ($responseData->get('status') != 200) {
            return response([
                'sucesso' => false,
                'mensagem' => 'Falha ao gerar extrato'
            ], 400);
        }

        return response([
            'sucesso' => true,
            'mensagem' => 'extrato gerado com sucesso',
            'data' => $responseData->get('response')
        ], 200);

    }

    private function login()
    {
        $uuidEmpresa = env('BANCO_UUID_EMPRESA');
        $pixKey = env('CONTA_PIX_KEY');

        $loginData = collect($this->loginService->loginBank([
            'username' => env('CONTA_USER_DOCUMENT'),
            'password' => env('CONTA_USER_PASSWORD')
        ], $uuidEmpresa));

        return $loginData->get('response')->get('token');
    }
}

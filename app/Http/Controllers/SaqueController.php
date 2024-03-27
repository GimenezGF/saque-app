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
        $uuidEmpresa = '559bd72a-c4f9-4b34-84b5-9508cedfdcc3';
        $pixKey = '9b9aa5a1-3d1c-4590-a807-0e180172789b';

        $loginData = collect($this->loginService->login([
            'username' => '16511462765',
            'password' => 'Cartos@123'
        ], $uuidEmpresa));

        $token = $loginData->get('response')->get('token');

        $dataQrCode = [
            'receiverKey' => $pixKey,
            'merchantCity' => 'Porto Alegre',
            'value' => $request->valor,
            'txId' => 'gimenez99999',
            'additionalInfo' => $request->mensagem
        ];

        $responseData = collect($this->areaPixService->sendPixStatic($token, $dataQrCode));
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
        $uuidEmpresa = '559bd72a-c4f9-4b34-84b5-9508cedfdcc3';
        $loginData = collect($this->loginService->login([
            'username' => '16511462765',
            'password' => 'Cartos@123'
        ], $uuidEmpresa));

        $token = $loginData->get('response')->get('token');
        $accountId = 'cea1078c-899d-4db8-bd90-6007e0688e2b';
        $uuidEmpresa = '559bd72a-c4f9-4b34-84b5-9508cedfdcc3';

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

}

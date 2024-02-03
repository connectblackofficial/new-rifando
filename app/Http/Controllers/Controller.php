<?php

namespace App\Http\Controllers;

use App\Enums\FileUploadTypeEnum;
use App\Exceptions\UserErrorException;
use App\Helpers\FileUploadHelper;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Process\Process;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $siteConfig;
    public $fieldsRifa = [
        'id',
        'maximo',
        'minimo',
        'modo_de_jogo',
        'favoritar',
        'type_raffles',
        'qtd',
        'status',
        'ganho_afiliado',
        'price',
        'slug',
        'qtd_ranking',
        'expiracao',
        'parcial',
        'name',
        'subname',
        'gateway'
    ];

    public function pull()
    {
        $command = new Process("git pull");
        $command->setWorkingDirectory(base_path());
        $command->run();

        $response = [];

        if ($command->isSuccessful()) {
            $response['pull'] = 'ok';
        } else {
            $response['pull'] = 'Erro';
        }

        $migrate = new Process("/usr/local/bin/ea-php74 artisan migrate");
        $migrate->setWorkingDirectory(base_path());
        $migrate->run();

        if ($migrate->isSuccessful()) {
            $response['migrate'] = 'ok';
        } else {
            $response['migrate'] = 'Erro';
        }

        dd($response);
    }

    public function updateOldRaffles()
    {
        $command = new Process("php artisan update:raffles");
        $command->setWorkingDirectory(base_path());
        $command->run();

        $response = [];

        if ($command->isSuccessful()) {
            $response['update-raffles'] = 'ok';
        } else {
            $response['update-raffles'] = 'Erro';
        }

        dd($response);
    }

    public function formatMoney($value)
    {
        $value = str_replace(".", "", $value);
        $value = str_replace(",", ".", $value);

        return $value;
    }

    public function migrate()
    {
        $command = new Process("php artisan migrate");
        $command->setWorkingDirectory(base_path());
        $command->run();

        if ($command->isSuccessful()) {
            dd('ok');
        } else {
            dd('erro');
        }
    }

    public function updateFooter()
    {
        DB::table('sites')
            ->where('sites.id', 1)
            ->update(
                [
                    'footer' => 'Marquinhos do paredão resultado pela loteria federal 🍀',
                ]
            );

        dd('ok');
    }

    function catchAndRedirect($callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return parseExceptionMessage($e);
            return redirect()->back()->withErrors(parseExceptionMessage($e));
        }
    }

    function catchJsonResponse($callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return response()->json(['error' => 'error', 'msg' => parseExceptionMessage($e)], 404);
        }
    }

    public function processAjaxResponseWithTrans(array $formData, array $rules, $callback, $redirect = false)
    {

        return $this->processAjaxResponse($formData, $rules, $callback, $redirect, true);

    }

    public function processAjaxResponse(array $formData, array $rules, $callback, $redirect = false, $withTrans = false)
    {
        $validator = Validator::make($formData, $rules);
        if (count($rules) > 0 && $validator->fails()) {
            $res = $validator->errors()->getMessages();
            $return["error"] = true;
            $return["error_message"] = $res;
            return response()->json($return);
        } else {
            try {
                if ($withTrans) {
                    \DB::beginTransaction();
                }
                $response = $callback();
                if (!$response) {
                    throw new UserErrorException("Ocorreu um erro desconhecido.");
                } else {
                    if (!empty($response)) {
                        $return["data"] = $response;
                    }
                    $return["success"] = true;
                    $return["success_message"] = "Operação realizada com sucesso.";
                    if (isset($response['redirect_url']) && validateUrl($response['redirect_url'])) {
                        $return['redirect'] = true;
                        $return['redirect_url'] = $response['redirect_url'];
                    } elseif ($redirect === true) {
                        $return['redirect'] = true;
                        $return['redirect_url'] = url()->previous();
                    } else if (validateUrl($redirect)) {
                        $return['redirect'] = true;
                        $return['redirect_url'] = $redirect;
                    }
                    if ($withTrans) {
                        \DB::commit();
                    }
                    return response()->json($return);
                }
            } catch (UserErrorException $e) {
                if ($withTrans) {
                    \DB::rollBack();
                }
                $return["error"] = true;
                $return["error_message"] = ['id' => parseExceptionMessage($e)];
                return response()->json($return);
            } catch (\Exception $e) {
                if ($withTrans) {
                    \DB::rollBack();
                }
                $return["error"] = true;
                $return["error_message"] = ['id' => parseExceptionMessage($e)];
                return response()->json($return);
            }
        }
    }
}

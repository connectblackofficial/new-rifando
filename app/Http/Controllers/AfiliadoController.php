<?php

namespace App\Http\Controllers;

use App\Models\AffiliateEarning;
use App\Models\AffiliateRaffle;
use App\Models\AffiliateWithdrawalRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AfiliadoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            if (!$user->afiliado) {
                Auth::logout();
                return redirect()->route('afiliado.home');
            }


            $data = [
                'rifas' => Product::siteOwner()->where('ganho_afiliado', '>', 0)->get()
            ];

            return view('afiliados.home', $data);
        } else {
            return view('afiliados.login');
        }
    }

    public function rifasAtivas()
    {
        $rifas = Product::siteOwner()->where('ganho_afiliado', '>', 0)->where('status', '=', 'Ativo')->get();
        $user = Auth::user();


        foreach ($rifas as $rifa) {
            $afiliado = AffiliateRaffle::siteOwner()->where('product_id', '=', $rifa->id)->where('afiliado_id', '=', $user->id)->get();

            if ($afiliado->count() > 0) {
                $rifa->checkAfiliado = true;
                $af = AffiliateRaffle::siteOwner()->where('product_id', '=', $rifa->id)->where('afiliado_id', '=', $user->id)->first();
                $rifa->getAfiliadoToken = $af->token;

            } else {
                $rifa->checkAfiliado = false;
                $rifa->getAfiliadoToken = '';
            }
        }


        $data = [
            'rifas' => $rifas
        ];

        return view('afiliados.rifasAtivas', $data);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'afiliado' => true,
            'parent_id' => getSiteOwnerId()
        ];

        if (Auth::attempt($credentials)) {

            return redirect()->route('afiliado.home');
        }

        return back()->withInput()->withErrors('Usuário e/ou Senha incorretos');
    }

    public function cadastro()
    {
        return view('afiliados.cadastro');
    }

    public function novo(Request $request)
    {
        if ($request->senha != $request->conf_senha) {
            return back()->withInput()->withErrors('Senhas não conferem!');
        }

        if (User::siteOwner()->where('email', '=', $request->email)->count() > 0) {
            return back()->withInput()->withErrors('Email já possui cadastro!');
        }

        $user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'telephone' => '',
            'status' => '1',
            'password' => bcrypt($request->senha),
            'cpf' => $request->cpf,
            'pix' => $request->pix,
            'afiliado' => true,
            'parent_id' => getSiteOwnerId()
        ]);

        Auth::login($user);

        return redirect()->route('afiliado.home');
    }

    public function home()
    {
        return view('afiliados.home');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('afiliado.home');
    }

    public function pagamentos()
    {
        $ganhos = AffiliateEarning::select('ganhos_afiliados.*')
            ->join('participant', 'participant.id', '=', 'ganhos_afiliados.participante_id')
            ->where('afiliado_id', '=', Auth::user()->id)
            ->where('participant.pagos', '>', 0)
            ->where("participant.user_id", getSiteOwnerId())
            ->get();

        $data = [
            'ganhos' => $ganhos,
            'disponivel' => $ganhos->where('solicitacao_id', '=', null)->sum('valor'),
            'solicitado' => $ganhos->where('solicitacao_id', '!=', null)->where('pago', '=', false)->sum('valor'),
            'recebido' => $ganhos->where('solicitacao_id', '!=', null)->where('pago', '=', true)->sum('valor'),
        ];


        return view('afiliados.pagamentos', $data);
    }

    public function afiliar($idRifa)
    {
        AffiliateRaffle::create([
            'product_id' => $idRifa,
            'afiliado_id' => Auth::user()->id,
            'token' => uniqid(),
            'user_id' => getSiteOwnerId()
        ]);

        return back()->with(['message' => 'Afiliado com sucesso!']);
    }

    public function solicitarSaque()
    {
        try {
            $afiliadoId = Auth::user()->id;

            $ganhosPendentes = AffiliateEarning::siteOwner()->where('afiliado_id', '=', $afiliadoId)->where('solicitacao_id', '=', null)->sum('valor');
            if ($ganhosPendentes == 0) {
                return back()->withErrors('Você não tem nenhum valor disponível para saque!');
            }

            $solicitacao = AffiliateWithdrawalRequest::create([
                'afiliado_id' => $afiliadoId,
                'user_id' => getSiteOwnerId()
            ]);

            AffiliateEarning::siteOwner()->where('afiliado_id', '=', $afiliadoId)->update([
                'solicitacao_id' => $solicitacao->id
            ]);

            return back()->with(['message' => 'Solicitação de saque realizada com sucesso!']);
        } catch (\Throwable $th) {
            return back()->withErrors('Erro interno no sistema!');
        }
    }
}

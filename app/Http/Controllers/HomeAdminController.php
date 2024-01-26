<?php

namespace App\Http\Controllers;

use App\Models\AutoMessage;
use App\Models\Customer;
use App\Models\Participant;
use App\Models\Product;
use App\Models\WhatsappMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeAdminController extends Controller
{
    public function index()
    {
        $participantes = Participant::siteOwner()->select('valor', 'reservados', 'pagos')->get();
        $rifas = Product::siteOwner()->where('status', '=', 'Ativo')->get();

        return view('admin.dashboard', [
            'participantes' => $participantes,
            'rifas' => $rifas
        ]);
    }

    public function wpp()
    {

        if (WhatsappMessage::siteOwner()->count() == 0) {
            for ($i = 0; $i < 6; $i++) {
                WhatsappMessage::create(['user_id' => getSiteOwnerId()]);
            }
        }

        $data = [
            'msgs' => WhatsappMessage::siteOwner()->get(),
            'autoMessages' => AutoMessage::siteOwner()->where('id', '>', 0)->where('destinatario', '=', 'cliente')->orderBy('destinatario')->get(),
            'config' => getSiteConfigId()
        ];


        return view('wpp-msgs.index', $data);
    }

    public function wppSalvar(Request $request)
    {
        foreach ($request->id as $key => $value) {
            $whatsappMensagem = WhatsappMessage::getByIdWithSiteCheck($value);
            if (isset($whatsappMensagem['id'])) {
                $whatsappMensagem->update([
                    'titulo' => $request->titulo[$value],
                    'msg' => nl2br($request->msg[$value]),
                ]);
            }
        }

        foreach ($request->idAuto as $key => $value) {

            $autoMessage = AutoMessage::getByIdWithSiteCheck($value);
            if (isset($autoMessage['id'])) {
                $autoMessage->update([
                    'msg' => $request->msgAuto[$value]
                ]);
            }
        }

        getSiteConfig()->update([
            'token_api_wpp' => $request->token_api_wpp
        ]);

        return redirect()->back()->with('success', 'Mensagens atualizadas com sucesso!');
    }

    public function clientes(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $clientes = Customer::siteOwner()->where(function (Builder $query) use ($search) {
                $query->orWhere('nome', 'like', '%' . $search . '%');
                $query->orWhere('telephone', 'like', '%' . $search . '%');
                $query->orWhere('cpf', 'like', '%' . $search . '%');
                $query->orWhere('email	', 'like', '%' . $search . '%');
            })->paginate(10);
        } else {
            $clientes = Customer::siteOwner()->paginate(10);
        }

        $data = [
            'clientes' => $clientes,
            'search' => $request->search
        ];

        return view('clientes.index', $data);
    }

    public function editarCliente($id)
    {
        $cliente = Customer::getByIdWithSiteCheck($id);
        if (!isset($cliente['id'])) {
            abort(404);
        }

        $data = [
            'cliente' => $cliente
        ];

        return view('clientes.editar', $data);

    }

    public function updateCliente($id, Request $request)
    {
        $cliente = Customer::getByIdWithSiteCheck($id);
        if (!isset($cliente['id'])) {
            abort(404);
        }

        if ($cliente->telephone != $request->telephone) {
            if (Customer::siteOwner()->where('telephone', '=', $request->telephone)->count() > 0) {
                return back()->withErrors('Telefone já cadastrado.');
            }
        }

        $cliente->update([
            'nome' => $request->nome,
            'telephone' => $request->telephone
        ]);

        Participant::siteOwner()->where('customer_id', '=', $cliente->id)->update([
            'name' => $request->nome,
            'telephone' => $request->telephone
        ]);

        return redirect()->route('clientes')->with('success', 'Cliente atualizado com sucesso!');
    }
}


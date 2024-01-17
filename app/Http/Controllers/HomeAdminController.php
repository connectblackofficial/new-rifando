<?php

namespace App\Http\Controllers;

use App\AutoMessage;
use App\Environment;
use App\Models\Customer;
use App\Models\Participant;
use App\Models\Product;
use App\WhatsappMensagem;
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

        if (WhatsappMensagem::siteOwner()->count() == 0) {
            for ($i = 0; $i < 6; $i++) {
                WhatsappMensagem::create(['user_id' => getSiteOwner()]);
            }
        }

        $data = [
            'msgs' => WhatsappMensagem::siteOwner()->get(),
            'autoMessages' => AutoMessage::siteOwner()->where('id', '>', 0)->where('destinatario', '=', 'cliente')->orderBy('destinatario')->get(),
            'config' => getSiteConfigId()
        ];


        return view('wpp-msgs.index', $data);
    }

    public function wppSalvar(Request $request)
    {
        foreach ($request->id as $key => $value) {
            $whatsappMensagem = WhatsappMensagem::getByIdWithSiteCheck($value);
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

        Environment::where("id", getSiteConfigId())->update([
            'token_api_wpp' => $request->token_api_wpp
        ]);

        return redirect()->back()->with('success', 'Mensagens atualizadas com sucesso!');
    }

    public function clientes(Request $request)
    {
        if ($request->search) {
            $clientes = Customer::siteOwner()->where('nome', 'like', '%' . $request->search . '%')->get();
        } else {
            $clientes = Customer::siteOwner()->get();
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


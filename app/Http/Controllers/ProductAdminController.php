<?php


namespace App\Http\Controllers;

use App\Enums\FileUploadTypeEnum;
use App\Helpers\FileUploadHelper;
use App\Models\Premio;
use App\Models\Product;
use App\Models\Product as ModelsProduct;
use App\Models\ProductImage;
use App\Models\Promocao;
use App\Models\Raffle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProductAdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


    }

    /*public function update(Request $request, $id){
        $products = Product::find(6)
            ->select('products.id', 'products.name', 'products.price', 'products.type_raffles', 'products.winner', 'products.slug', 'products_images.name as image', 'raffles.number as total_number', 'product_description.description as description', 'products.status', 'products.draw_date', 'products.draw_prediction', 'products.visible', 'products.favoritar')
            ->join('products_images', 'products.id', 'products_images.product_id')
            ->join('product_description', 'products.id', 'product_description.product_id')
            ->join('raffles', 'products.id', 'raffles.product_id')           
            ->groupBy('products.id')
            ->orderBy('products.id', 'DESC')
            ->get();
        return view('my-sweepstakes', [           
            'products' => $products
        ]);
        
    }*/
    public function destroy(Request $request)
    {

        $id = $request->input('deleteId');

        $product_delete = Product::getByIdWithSiteCheck($id);
        if (!isset($product_delete['id'])) {
            return back()->withErrors(['Produto não encontrado.']);
        }

        $path = 'numbers/' . $product_delete->id . '.json';
        if (file_exists($path)) {
            unlink($path);
        }

        $name_rifa = $product_delete->name;
        $product_delete->delete();
        return redirect('/meus-sorteios')->with('success', 'Rifa (' . $name_rifa . ') excluida com Sucesso');
    }


    public function addProduct(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|max:6',
            'images' => 'required|max:3',
            'numbers' => 'required|min:1|max:7',
            'description' => env('REQUIRED_DESCRIPTION') ? 'required|max:50000' : '',
            'minimo' => 'required',
            'maximo' => 'required',
            'expiracao' => 'required|min:0',
            'gateway' => 'required|in:mp,asaas,paggue'
        ]);
        $siteConfig = getSiteConfig();
        if ($request->gateway == 'mp' && !$siteConfig->key_pix) {
            return Redirect::back()->withErrors('Para utilizar o gateway de pagamento Mercado Pago é necessário informar o token na sessão "Meu Perfil"');
        }

        if ($request->gateway == 'asaas' && !$siteConfig->token_asaas) {
            return Redirect::back()->withErrors('Para utilizar o gateway de pagamento ASAAS é necessário informar o token na sessão "Meu Perfil"');
        }

        if ($request->gateway == 'paggue' && (!$siteConfig->paggue_client_key || !$siteConfig->paggue_client_secret)) {
            return Redirect::back()->withErrors('Para utilizar o gateway de pagamento Paggue é necessário informar o CLIENT KEY e CLIENT SECRET na sessão "Meu Perfil"');
        }


        $product = Product::create([
            'name' => $request->name,
            'subname' => $request->subname,
            'price' => $request->price,
            'qtd' => $request->numbers,
            'expiracao' => $request->expiracao,
            'processado' => true,
            'status' => 'Ativo',
            'type_raffles' => 'automatico',
            'slug' => createSlug($request->name),
            'user_id' => getSiteOwner(),
            'visible' => 0,
            'minimo' => $request->minimo,
            'maximo' => $request->maximo,
            'modo_de_jogo' => $request->modo_de_jogo,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'gateway' => $request->gateway
        ]);
        // criando as promocoes
        $product->createPromos();

        // Premios
        $dadosRequest = $request->all();

        $product->createDefaultPremiums($dadosRequest);


        $files = $request->file('images');

        if ($request->hasFile('images')) {
            foreach ($files as $key => $images) {
                $upload_imagename = $key . time() . '.' . $images->getClientOriginalExtension();
                $upload_url = public_path('/products') . '/' . $upload_imagename;
                $filename = $this->compress_image($_FILES["images"]["tmp_name"][$key], $upload_url, 80);
                DB::table('products_images')->insert(
                    [
                        'name' => $upload_imagename,
                        'product_id' => $product->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'user_id' => getSiteOwner()
                    ]
                );
            }
        }

        if (str_starts_with($request->modo_de_jogo, 'fazendinha')) {

            if ($request->modo_de_jogo == 'fazendinha-completa') {
                for ($i = 1; $i <= 25; $i++) {
                    $number = 'g' . $i;
                    Raffle::simpleCreate($number, $product->id, $product->user_id);
                }
            } else if ($request->modo_de_jogo == 'fazendinha-meio') {
                for ($i = 1; $i <= 25; $i++) {
                    $number = 'g' . $i . '-le';
                    Raffle::simpleCreate($number, $product->id, $product->user_id);
                    $number = 'g' . $i . '-ld';
                    Raffle::simpleCreate($number, $product->id, $product->user_id);
                }
            }

        } else {
            $qtdNumbers = $request->numbers;

            $arr = [];
            $qtdZeros = strlen((string)$qtdNumbers);
            if ($request->qtd_zeros != null) {
                $qtdZeros = $request->qtd_zeros + 1;
            }

            for ($x = 0; $x < $qtdNumbers; $x++) {
                $nbr = str_pad($x, $qtdZeros, '0', STR_PAD_LEFT);
                array_push($arr, $nbr);
            }

            $rifa = ModelsProduct::getByIdWithSiteCheck($product->id);
            $stringNumbers = implode(",", $arr);
            $rifa->numbers = $stringNumbers;
            $rifa->save();

        }


        DB::table('product_description')->insert(
            [
                'description' => $request->description,
                'product_id' => $product,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'user_id' => $product->user_id
            ]
        );

        return redirect()->back()->with('success', 'Cadastro da Rifa efetuado com sucesso!');
    }


    public function alterProduct(Request $request)
    {

        $validatedData = $request->validate([
            //'name' => 'required|max:255',
            //'price' => 'required|max:6',
            'images' => 'required',
            //'numbers' => 'required|min:1|max:5',
            //'description' => 'required|max:5000',
        ]);

        $files = $request->file('images');

        if ($request->hasFile('images')) {

            ProductImage::siteOwner()->where('product_id', '=', $request->product_id)->delete();

            foreach ($files as $key => $images) {
                $upload_imagename = $key . time() . '.' . $images->getClientOriginalExtension();
                $upload_url = public_path('/products') . '/' . $upload_imagename;

                $filename = $this->compress_image($_FILES["images"]["tmp_name"][$key], $upload_url, 80);

                DB::table('products_images')->insert(
                    [
                        'name' => $upload_imagename,
                        'product_id' => $request->product_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'user_id' => getSiteOwner()
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Cadastro efetuado com sucesso!');
    }

    public function alterarLogo(Request $request)
    {
        try {
            $imageUpload = new FileUploadHelper($request, 'logo', FileUploadTypeEnum::Image);
            $imageUrl = $imageUpload->upload();

            getSiteConfig()->update([
                'logo' => $imageUrl
            ]);
            return redirect()->back()->with('success', 'Logo alterada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(parseExceptionMessage($e));
        }
    }

    public function compress_image($source_url, $destination_url, $quality)
    {
        $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source_url);
            $image = imagescale($image, 1080, 1080);
            //dd($imgResized);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source_url);
            $image = imagescale($image, 1080, 1080);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source_url);
            $image = imagescale($image, 1080, 1080);
        }

        imagejpeg($image, $destination_url, $quality);

        return $destination_url;
    }

    public function drawDate(Request $request)
    {
        $validatedData = $request->validate([
            'drawdate' => 'required',
        ]);

        //dd($request->drawdate);
        $originalDate = $request->drawdate;
        $newDate = date('Y-m-d H:i', strtotime(str_replace("/", "-", $originalDate)));

        //dd($newDate);

        DB::table('products')
            ->where('id', $request->product_id)
            ->where('user_id', getSiteOwner())
            ->update(
                [
                    'status' => 'Agendado',
                    'draw_date' => $newDate
                ]
            );

        return redirect()->back()->with('success', 'Cadastro efetuado com sucesso!');
    }

    public function drawPrediction(Request $request)
    {
        $validatedData = $request->validate([
            'drawPrediction' => 'required',
        ]);

        //dd($request->drawdate);
        $originalDate = $request->drawPrediction;
        $newDate = date('Y-m-d H:i', strtotime(str_replace("/", "-", $originalDate)));

        //dd($newDate);

        DB::table('products')
            ->where('id', $request->product_id)
            ->where('user_id', getSiteOwner())
            ->update(
                [
                    'draw_prediction' => $newDate
                ]
            );

        return redirect()->back()->with('success', 'Cadastro efetuado com sucesso!');
    }

    public function alterStatusProduct(Request $request)
    {
        if (isset($request['switch'])) {
            //dd($request->all());

            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwner())
                ->update(
                    [
                        'visible' => 1,
                    ]
                );
        } else {
            //dd("N EXISTE A VARIAVEL");

            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwner())
                ->update(
                    [
                        'visible' => 0,
                    ]
                );
        }

        return redirect()->back();
    }

    public function favoritarRifa(Request $request)
    {
        if (isset($request['switch-favoritar'])) {
            //dd($request->all());

            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwner())
                ->update(
                    [
                        'favoritar' => 1,
                    ]
                );
        } else {
            //dd("N EXISTE A VARIAVEL");

            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwner())
                ->update(
                    [
                        'favoritar' => 0,
                    ]
                );
        }

        return redirect()->back();
    }

    public function alterWinnerProduct(Request $request)
    {

        //dd(nl2br($request->winner));

        if ($request->winner == "") {
            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', Auth::user()->id)
                ->update(
                    [
                        'status' => 'Agendado',
                        'winner' => null,
                    ]
                );
        } else {
            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwner())
                ->update(
                    [
                        'status' => 'Finalizado',
                        'winner' => nl2br($request->winner),
                    ]
                );
        }

        return redirect()->back();
    }

    public function alterTypeRafflesProduct(Request $request)
    {

        //dd($request->all());

        DB::table('products')
            ->where('id', $request->product_id)
            ->where('user_id', getSiteOwner())
            ->update(
                [
                    'type_raffles' => $request->type,
                ]
            );

        return redirect()->back();
    }

    public function addFoto(Request $request)
    {
        // $request->validate([
        //     'fotos' => 'required|mimes:png,jpeg,jpg'
        // ]);


        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $key => $images) {

                $upload_imagename = $key . time() . '.' . $images->getClientOriginalExtension();
                $upload_url = public_path('/products') . '/' . $upload_imagename;

                $filename = $this->compress_image($_FILES["fotos"]["tmp_name"][$key], $upload_url, 80);

                DB::table('products_images')->insert(
                    [
                        'name' => $upload_imagename,
                        'product_id' => $request->idRifa,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Foto(s) adicionadas com sucesso!');
    }

    public function duplicar(Request $request)
    {
        $rifa = ModelsProduct::find($request->product);

        $product = DB::table('products')->insertGetId(
            [
                'name' => $request->name,
                'subname' => $rifa->subname,
                'price' => $request->price,
                'qtd' => $request->numbers,
                'expiracao' => $rifa->expiracao,
                'processado' => true,
                'status' => 'Ativo',
                'type_raffles' => 'automatico',
                'slug' => createSlug($rifa->name),
                'user_id' => Auth::user()->id,
                'visible' => 0,
                'minimo' => $rifa->minimo,
                'maximo' => $rifa->maximo,
                'modo_de_jogo' => $rifa->modo_de_jogo,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'gateway' => $rifa->gateway
            ]
        );

        // criando as promocoes
        foreach ($rifa->promocoes() as $promocao) {
            Promocao::create([
                'qtdNumeros' => $promocao->qtdNumeros,
                'desconto' => $promocao->desconto,
                'valor' => $promocao->valor,
                'product_id' => $product,
            ]);
        }

        // Premios
        foreach ($rifa->premios() as $premio) {
            Premio::create([
                'product_id' => $product,
                'ordem' => $premio->id,
                'descricao' => $premio->descricao,
                'ganhador' => '',
                'cota' => ''
            ]);
        }

        // Imagem
        DB::table('products_images')->insert(
            [
                'name' => $rifa->imagem()->name,
                'product_id' => $product,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );


        if (str_starts_with($rifa->modo_de_jogo, 'fazendinha')) {
            if ($rifa->modo_de_jogo == 'fazendinha-completa') {
                for ($i = 1; $i <= 25; $i++) {
                    DB::table('raffles')->insert(
                        [
                            'number' => 'g' . $i,
                            'status' => 'Disponível',
                            'product_id' => $product,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]
                    );
                }
            } else if ($rifa->modo_de_jogo == 'fazendinha-meio') {
                for ($i = 1; $i <= 25; $i++) {
                    DB::table('raffles')->insert(
                        [
                            'number' => 'g' . $i . '-le',
                            'status' => 'Disponível',
                            'product_id' => $product,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]
                    );

                    DB::table('raffles')->insert(
                        [
                            'number' => 'g' . $i . '-ld',
                            'status' => 'Disponível',
                            'product_id' => $product,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]
                    );
                }
            }

        } else {
            $qtdNumbers = $rifa->numbers;

            $arr = [];
            $qtdZeros = strlen((string)$qtdNumbers);
            if ($rifa->qtd_zeros != null) {
                $qtdZeros = $rifa->qtd_zeros + 1;
            }

            for ($x = 0; $x < $qtdNumbers; $x++) {
                $nbr = str_pad($x, $qtdZeros, '0', STR_PAD_LEFT);
                array_push($arr, $nbr);
            }

            $newRifa = ModelsProduct::find($product);
            $stringNumbers = implode(",", $arr);
            $newRifa->numbers = $stringNumbers;
            $newRifa->update();
        }


        DB::table('product_description')->insert(
            [
                'description' => $rifa->descricao(),
                'product_id' => $product,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        return redirect()->back()->with('success', 'Rifa copiada com sucesso!');
    }
}

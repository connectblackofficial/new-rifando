<?php


namespace App\Http\Controllers;

use App\Enums\FileUploadTypeEnum;
use App\Exceptions\UserErrorException;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\SiteProductStoreRequest;
use App\Models\Premio;
use App\Models\Product;
use App\Models\Product as ModelsProduct;
use App\Models\ProductDescription;
use App\Models\ProductImage;
use App\Models\Promocao;
use App\Models\Raffle;
use App\Services\ProductService;
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


    public function addProduct(SiteProductStoreRequest $request)
    {
dd($request->file('images'));
        $storeProduct = function () use ($request) {
            $productService = new ProductService();
            dd($request->file('images'));
            $productService->processAddProduct($request->all(),$request->file('images'));
            return redirect()->back()->with('success', 'Cadastro da Rifa efetuado com sucesso!');
        };
        return $this->catchAndRedirect($storeProduct);
    }

    public function alterProduct(Request $request)
    {
        $request->validate([
            'images' => 'required',
        ]);
        $storeProduct = function () use ($request) {
            $product = Product::getByIdWithSiteCheckOrFail($request->product_id);
            $productService = new ProductService();
            $productService->processImages($product, $request);
            return redirect()->back()->with('success', 'Cadastro efetuado com sucesso!');
        };
        return $this->catchAndRedirect($storeProduct);

    }


    public function alterarLogo(Request $request)
    {
        $imageUpload = function () use ($request) {
            if (!$request->hasFile('logo')) {
                throw  UserErrorException::emptyImage();
            }
            $imageUpload = new FileUploadHelper($request->file('logo'), FileUploadTypeEnum::Image);
            $imageUrl = $imageUpload->upload();
            getSiteConfig()->update([
                'logo' => $imageUrl
            ]);
            return redirect()->back()->with('success', 'Logo alterada com sucesso!');
        };

        return $this->catchAndRedirect($imageUpload);
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
            ->where('user_id', getSiteOwnerId())
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
            ->where('user_id', getSiteOwnerId())
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
                ->where('user_id', getSiteOwnerId())
                ->update(
                    [
                        'visible' => 1,
                    ]
                );
        } else {
            //dd("N EXISTE A VARIAVEL");

            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwnerId())
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
                ->where('user_id', getSiteOwnerId())
                ->update(
                    [
                        'favoritar' => 1,
                    ]
                );
        } else {
            //dd("N EXISTE A VARIAVEL");

            DB::table('products')
                ->where('id', $request->product_id)
                ->where('user_id', getSiteOwnerId())
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
                ->where('user_id', getSiteOwnerId())
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
            ->where('user_id', getSiteOwnerId())
            ->update(
                [
                    'type_raffles' => $request->type,
                ]
            );

        return redirect()->back();
    }

    public function addFoto(Request $request)
    {
        $changeLogo = function () use ($request) {
            $product = Product::getByIdWithSiteCheckOrFail($request->idRifa);
            if ($request->hasFile('fotos')) {
                $productService = new ProductService();
                $productService->processImages($product, $request, 'fotos');
            } else {
                throw UserErrorException::emptyImage();
            }
            return redirect()->back()->with('success', 'Foto(s) adicionadas com sucesso!');
        };
        return $this->catchAndRedirect($changeLogo);

    }

    public function duplicar(Request $request)
    {
        $rifa = ModelsProduct::getByIdWithSiteCheck($request->product);

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

            $newRifa = ModelsProduct::getByIdWithSiteCheck($product);
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

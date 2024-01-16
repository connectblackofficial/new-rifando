<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /*public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        //Auth::guard($this->getGuard())->login($this->create($request->all()));
        // Originally the parameter is $this->redirectPath()
        return redirect()->to('/login');
    }*/

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => 1,
            'telephone' => $data['telephone'],
            'password' => bcrypt($data['password']),
        ]);

        //SE TEM RETORNO DO ID DO USUARIO CRIADO CADASTRA O RESTANTE
        if ($user['id']) {

            //CRIA AS CONFIGS DOS AMBIENTES NOME E INSTA
            DB::table('consulting_environments')->insert([
                [
                    'name' => $data['work'],
                    'instagram' => $data['instagram'],
                    'user_id' => $user->id
                ]
            ]);
        }

        return $user;
    }
}

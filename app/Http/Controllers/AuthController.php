<?php

namespace App\Http\Controllers;

use App\Models\Comprador;
use App\Models\User;
use App\Models\Vendedor;
use App\Utils\Utils;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){ 
            $role = Auth::user()->type;

            return redirect()->route($role.'/dashboard');
        }

        $erros = [];
        $email = User::where('email', $request->email)->count();
        if($email == 0)
            $erros['email'] = "Não existe este email no sistema.";
        else
            $erros['password'] = "A senha está incorreta.";

        return back()->withErrors($erros);
    }

    public function viewRegister(Request $request)
    {
        return view('auth/register', ["states" => Utils::states]);
    }

    public function logout(Request $request)
    {        
        Session::flush();
        Auth::logout();

        return redirect('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isComprador = $request->typeUser == 'comprador';

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                PasswordRule::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => 'required|same:password',
            'typeUser' => [
                'required',
                Rule::in(['vendedor', 'comprador'])
            ],
            'cpf' => [
                Rule::requiredIf($isComprador),
                $isComprador ? 'cpf' : '',
                $isComprador ? 'unique:compradors,cpf' : ''
            ],
            'birth_date' => [
                Rule::requiredIf($isComprador),
                $isComprador ? 'date' : '',
                $isComprador ? 'before:'.date('Y-m-d') : '' 
            ],
            'state' => [
                Rule::requiredIf($isComprador),
                $isComprador ? Rule::in([
                    "AC", "AL", "AP", "AM", "BA",
                    "CE", "DF", "ES", "GO", "MA",
                    "MT", "MS", "MG", "PA", "PB",
                    "PR", "PE", "PI", "RJ", "RN",
                    "RS", "RO", "RR", "SC", "SP",
                    "SE", "TO"
                ]) : ''
            ],
            'city' => Rule::requiredIf($isComprador),
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = $request->typeUser;

        if($user->save()){

            if($isComprador){
                $comprador = new Comprador();
                $comprador->cpf = $request->cpf;
                $comprador->birth_date = $request->birth_date;
                $comprador->state = $request->state;
                $comprador->city = $request->city;
                $comprador->credits = 0;

                $user->comprador()->save($comprador);
            }
            else{
                $vendedor = new Vendedor();
                $vendedor->status = 'P';
                $vendedor->credits = 0;

                $user->vendedor()->save($vendedor);
            }
        
            Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            event(new Registered($user));

            return redirect()->route($user->type.'/dashboard');
        }
    }

    public function viewSendEmailVerify() {
        if(empty(Auth::user()->email_verified_at))
            return view('auth/verify');

        return redirect()->route('comprador/dashboard');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    public function verificateEmail(EmailVerificationRequest $request) 
    {
        $request->fulfill();

        $typeUser = Auth::user()->type;

        if($typeUser == "comprador" && !Auth::user()->has_verified_email_before){
            $user = User::find(Auth::user()->id);
            $comprador = $user->comprador;
            $comprador->credits = 10000;
            $comprador->save();

            $user->has_verified_email_before = true;
            $user->save();
        }
    
        return redirect()->route($typeUser.'/dashboard');
    }    

    public function sendEmailVerification(Request $request) {

        $request->user()->sendEmailVerificationNotification();
     
        return redirect()->route('verification.notice')->with('message', 'Verification link sent!');
    }
}

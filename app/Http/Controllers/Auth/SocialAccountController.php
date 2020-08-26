<?php

namespace App\Http\Controllers\Auth;

use App\SocialAccountsService;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SocialAccountController extends Controller
{
    public function redirectToProvider($provider) {
        //LineServiceProviderによりdriver(line)を使えるようにする
        return \Socialite::driver($provider)->redirect();
    }
    
    public function handleProviderCallback(Request $request, SocialAccountsService $accountService, $provider) {
        //例外(エラー)が起きた場合はログインにリダイレクト
        try {
            /*ログインしようとしているアカウントのユーザー情報。find関数では$user->getId()でprovider_idを呼び出す。
            link関数では$user->getIdでprovider_id属性の値に代入する*/
            $user = \Socialite::with($provider)->user();
        } catch(\Exception $e) {
            echo $e->getMessage();
            return redirect('/');
        }
        
        $authUser = $accountService->find($user, $provider);
        
        if($authUser) {//連携アカウントが登録済み
        /*既に存在しているユーザーインスタンスでアプリケーションにログインさせるため、
        loginメソッドにそのユーザーインスタンス($authUser)を指定し呼び出す*/
            Auth::login($authUser, true); 
            return redirect('/');
        } else {//まだ登録なし
            if ($request->user()) { /*既ログインの場合、連携アカウントとして登録し、アカウントに紐付ける
                                    $request->user()は認証済みユーザーのインスタンスを返す*/
                if ($accountService->link($request->user(), $user, $provider)) {
                    return redirect('/');
                } else { /*既に同じサービスでのアカウント登録があり、紐づけ失敗
                (一つのアカウントで二つのLINEアカウントに登録しようとしている)*/
                    return redirect('/')->withErrors(['social' => 'LINEでの連携ログインがすでに登録されています']);
                }
            } else { //未ログイン
                redirect('/admin/index');
            }
            
        }
    }
}

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
            if ($provider == "twitter") {
                $user = \Socialite::with($provider)->user(); //user()に関しては/anime_review/vendor/laravel/socialite/src/Two/User.php参照
            } else {
                $user = \Socialite::with($provider)->stateless()->user();
            }
        } catch(\Exception $e) {
            echo $e->getMessage();
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
                    return redirect('/login')->with('success', $provider."アカウント連携が完了しました。次回以降ログインの際は".$provider." LOGINをご利用できます");
                } else { /*既に同じサービスでのアカウント登録があり、紐づけ失敗
                (一つのアカウントで二つのLINEアカウントに登録しようとしている)*/
                    return redirect('/login')->withErrors(['social' => 'LINEでの連携��グインがすでに登録されています']);
                }
            } else { //未ログイン
                return redirect('/login')->withErrors(['social' => "連携アカウントが登録されていません。通常ログインを実施後、".$provider." LOGIN より連携アカウントを登録してください。"]);
            }
            
        }
    }
}

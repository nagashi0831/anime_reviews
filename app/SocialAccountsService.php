<?php

namespace App;

use App\User;
use App\LinkedSocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser; //=>installed.json参考

class SocialAccountsService 
{
    public function find(ProviderUser $providerUser, $provider) {
        $account = LinkedSocialAccount::where('provider_name', $provider)
                                    ->where('provider_id', $providerUser->getId())
                                    ->first();//getと違いLinkedSocialAccountのオブジェクトを単体で返す
        
        //一致するprovider_name provider_idがLinkedSocialAccountモデルに合った場合はUserモデルにリレーションをつなげる(LinkedSocialAccountのuser関数により)                      
        return $account != null ? $account->user : null;                            
    }
    
    public function link(User $user, ProviderUser $providerUser, $provider) {
        if (LinkedSocialAccount::where('provider_name', $provider)
                ->where('user_id', $user->id)->exists()) {
                    return null;
                } else {
                    //提供されたアカウントを新規にログイン中のユーザに紐付ける(LinkedSocialAccountテーブルにデータを追加)
                    $user->accounts()->create([
                        'provider_id' => $providerUser->getId(),
                        'provider_name' => $provider,
                        ]);
                    return $user;   
                }
    }
}

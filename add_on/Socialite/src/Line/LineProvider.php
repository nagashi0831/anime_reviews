<?php

namespace Add_on\Socialite\Line;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User;


class LineProvider extends Controller
{   
    
    public function getAccessToken($req){
        
        $headers = [ 'Content-Type: application/x-www-form-urlencoded'];
        $post_data = array(
            'grant_type' => 'authorization_code',
            'code' => $req['code'],
            'redirect_uri' => config('service.line')->redirect,
            'client_id' => config('service.line')->client_id,
            'client_secret' => config('service.line')->client_secret
            );
            ///oauth/tokenルートは、access_token、refresh_token、expires_in属性を含むJSONレスポンスを返す。
            $url = 'https://api.line.me/oauth2/v2.1/token';
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url); //取得するURLを指定
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //サーバー証明書の検証を行わない
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  //実行結果を文字列で返す
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);  //設定するHTTPヘッダフィールドの配列
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data)); //HTTP"POST"で設定する全てのデータ
            
            $res = curl_exec($curl);
            
            curl_close($curl);
            //APIで取得したデータはJSON形式のため、json_decode()関数を利用してJSON文字列を配列に変換
            $json = json_decode($res);
            $accessToken = $json->access_token;
            
            return $accessToken;
    }
    
    public function getProfile($at){
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $at));
        curl_setopt($curl, CURLOPT_URL, 'https://api.line.me/v2/profile');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $res = curl_exec($curl);
        curl_close($curl);
        
        $json = json_decode($res);
        return $json;

    }
    
    public function callback(Request $request){
        
        //LINEからアクセストークンを取得
        $accessToken = $this->getAccessToken($request);
        //プロフィール取得
        $profile = $this->getProfile($accessToken);
        
        return (new User())->setRaw($profile)->map([
            'id' => $profile->userId,
            'name' => $profile->displayName,
            'avatar_original' => $profile->pictureUrl,
            ]);
    }
}

/*'redirect_uri' => config('service.line')->redirect,
                'client_id' => config('service.line')->client_id,
                'client_secret' => config('service.line')->client_secret*/
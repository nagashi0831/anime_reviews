<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class LineLoginController extends Controller
{   //LINEログインに必要なパラメータを設定して、LINEの認証URLにリダイレクトさせる
    public function lineLogin(){
        //state生成
        $state = Str::random(40);
        \Cookie::queue('state', $state,100);
        
        //nonece生成
        $nonce = Str::random(40);
        \Cookie::queue('nonce',$nonce,100);
        
        // LINE認証
        $uri ="https://access.line.me/oauth2/v2.1/authorize?";
        $response_type = "response_type=code";
        $client_id = "&client_id=1654734147";
        $redirect_uri = "&redirect_uri=https://reviewanime.work/callback";
        $state_uri = "&state=".$state;
        $scope = "&scope=openid%20profile";
        $prompt = "&prompt=consent";
        $nonce_uri = "&nonce=";
        
        $uri = $uri.$response_type.$client_id.$redirect_uri.$state_uri.$scope.$prompt.$nonce_uri;
        
        return redirect($uri);
    }
    
    public function getAccessToken($req){
        
        $headers = [ 'Content-Type: application/x-www-form-urlencoded'];
        $post_data = array(
            'grant_type' => 'authorization_code',
            'code' => $req['code'],
            'resirect_uri' => 'https://reviewanime.work/callback',
            'client_id' => '1654734147',
            'client_secret' => 'c9ecd5fe9b9f3baa407e46697495c3c5'
            );
            $url = 'https://api.line.me/oauth2/v2.1/token';
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
            
            $res = curl_exec($curl);
            curl_close($curl);
            
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
        url_close($curl);
        
        $json = json_decode($res);
        return $json;

    }
    
    public function callback(Request $request){
        
        //LINEからアクセストークンを取得
        $accessToken = $this->getAccessToken($request);
        //プロフィール取得
        $profile = $this->getProfile($accessToken);
        
        return view('home', compact('profile'));
    }
}

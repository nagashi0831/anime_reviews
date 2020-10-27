<?php

namespace Add_on\Socialite\Line;

use InvalidArgumentException;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

//AbstractProviderのabstract protected functionによってこのクラスのメソッドは強制されている
class LineProvider extends AbstractProvider implements ProviderInterface
{   //認可URL取得(https://developers.line.biz/ja/docs/line-login/integrate-line-login-v2/#%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3%E3%81%AE%E3%83%95%E3%83%AD%E3%83%BC)参照
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://access.line.me/dialog/oauth/weblogin', $state);
    }
    //v2.1ではないことに注意
    protected function getTokenUrl()
    {
        return 'https://api.line.me/v2/oauth/accessToken';
    }
    //レスポンスを配列で取得
    
    //アクセストークンレスポンスを得るためのリクエスト配列を作成
    public function getAccessTokenResponse($code){
        
        $headers = [ 'Content-Type: application/x-www-form-urlencoded'];
        $post_data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
            );
            
            ///oauth/tokenルートは、access_token、refresh_token、expires_in属性を含むJSONレスポンスを返す。
            $url = $this->getTokenUrl();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url); //取得するURLを指定
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //サーバー証明書の検証を行わない
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  //実行結果を文字列で返す
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);  //設定するHTTPヘッダフィールドの配列
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data)); //HTTP"POST"で設定する全てのデータ
            
            $res = curl_exec($curl);
            curl_close($curl);
            error_log(print_r($res,true), 3, "./debug.log"); 
            //APIで取得したデータはJSON形式のため、json_decode()関数を利用してJSON文字列を配列に変換
            $json = json_decode($res, true);
            return $json;
            
    }
    
    //ユーザーのプロフィール情報をアクセストークンから取得
    public function getUserByToken($at){
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $at));
        curl_setopt($curl, CURLOPT_URL, 'https://api.line.me/v2/profile');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $res = curl_exec($curl);
        curl_close($curl);
        
        $json = json_decode($res, true);
        return $json;

    }
    
    /*引数の$userはgetUserByTokenの返り値としてAbstractProviderで設定される。
    => getUseByTokeメソッドで得たユーザ情報からユーザID、LINE名、プロフィール画像を取り出す*/
    public function mapUserToObject(array $user)
    {   
        
        return (new User())->setRaw($user)->map([
            'id' => $user['userId'],
            'name' => $user['displayName'],
            'avatar_original' => $user['pictureUrl'],
            ]);
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    public function setUp():void{
        parent::setUp();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public static function getAccessToken($req){
        
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
            \Debugbar::info($curl);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
            
            $res = curl_exec($curl);
            \Debugbar::info($res);
            curl_close($curl);
            
            $json = json_decode($res);
            $this->assertNotNull($json);
            $this->assertTrue($json);
            $accessToken = $json->access_token;
            
            return $accessToken;
    }
    
    public static function testcall(){
       $this->getAccessToken("aaa");
    }
    
   
}

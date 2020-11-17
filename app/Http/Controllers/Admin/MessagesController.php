<?php

namespace App\Http\Controllers\Admin;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index(Request $request, $partner)
    {   //チャット画面に遷移する際のaction
        $loginId = Auth::id();
        
        $ToRe = [
            'send' => $loginId,
            'receive' => $partner
            ];
        
        //送信、受信のメッセージを取得する
        $lookFor = Message::where('send', $loginId)->where('receive', $partner);
        $lookFor->orWhere(function($query) use($loginId, $partner){
            $query->where('send', $partner)->where('receive', $loginId);
        });
        
        $messages = $lookFor->get();
        
        return view('admin.anime.message', compact('ToRe', 'messages'));
    }
    
    public function store(Request $request)
    {   //メッセージを送信した際のaction
        //リクエストパラメータ取得
        $insertParam = [
            'send' => $request->input('send'),
            'receive' => $request->input('receive'),
            'message' => $request->input('message')
            ];
            
            //メッセージ内容保存
            try{
                Message::insert($insertParam);
            }catch (\Exception $e){
                return false;
            }
        return redirect('/admin/anime/message/'.$request->input('receive'));
    }
    
    public function indexMem(Request $request) 
    {   //メッセージを交わした人一覧を表示するaction
        $cond_name = $request->cond_name;
        if($cond_name != ''){
            //検索されたら検索結果を表示する
            $partnerInfos = User::where('name', $cond_name)->paginate(10);
        } else {
            
            $messagesInfos = Message::where('send', Auth::id())->get();
            $partnerIDs = array();
            foreach ($messagesInfos as $messagesInfo) {
                array_push($partnerIDs, $messagesInfo->receive);
            }
            $partnerIDs = array_unique($partnerIDs);
            $partnerInfos = User::whereIn('id', $partnerIDs)->paginate(10);
        }
        return view('admin.anime.messageIndex',['partnerInfos' => $partnerInfos, 
        'cond_name' => $cond_name
        ]);
    }
}

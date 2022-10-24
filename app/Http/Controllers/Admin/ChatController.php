<?php

namespace App\Http\Controllers\Admin;

use App\Chats\ChatMessage;
use App\Chats\ChatRoom;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Pusher;
use Carbon\Carbon;
use Input;
use Request;

class ChatController extends Controller
{
    public function index()
    {
        return view("admin.chat.index");
    }

    public function getChatList()
    {
        $chats = ChatRoom::where('is_ended', false)->where('admin_id', 0)->get();
        foreach($chats as $chat) {
            $unseen = ChatMessage::where('chat_room_id', $chat->id)->where('is_admin', false)->where('seen', false)->get();
            $chat->unseen = count($unseen);
        }
        return response()->json(['rooms' => $chats], 200);
    }

    public function getChatHistory() {
        $chats = ChatRoom::orderBy('id', 'desc')->get();
        return view("admin.chat.history.index", compact('chats'));
    }

    public function readClientChat($roomId) {
        $room = ChatRoom::find($roomId);
        $messages = ChatMessage::where('chat_room_id', $room->id)->get();
        // ChatMessage::where('chat_room_id', $room->id)->update(['seen' => 1]);
        foreach($messages as $message) {
            $message->date = date('H:i M d',strtotime($message->created_at));
        }
        return view("admin.chat.history.chat_history", compact('room', 'messages'));
    }

    public function clientChat($roomId) {
        $room = ChatRoom::find($roomId);
        $user = auth()->user();
        if($room->admin_id != $user->id && (int)$room->admin_id > 0) {
            alert()->warning('The Member has been already recieved by another Admin.', 'Warning!');
            return redirect()->route('admin.chat');
        }
        $messages = ChatMessage::where('chat_room_id', $room->id)->get();
        ChatMessage::where('chat_room_id', $room->id)->update(['seen' => 1]);
        foreach($messages as $message) {
            $message->date = date('H:i M d',strtotime($message->created_at));
        }
        return view("admin.chat.chat", compact('room', 'messages'));
    }

    public function saveMessage(Request $request, $roomId = '')
    {
        if(Request::ajax()) {
            $data = Input::all();
            $user = auth()->user();
            $room = ChatRoom::find($roomId);

            if($room->admin_id == 0) {
                $room->update(['admin_id' => $user->id]);
            }

            $message = ChatMessage::create([
                'chat_room_id' => $room->id,
                'user_id' => $user->id,
                'author' => $user->first_name." ".$user->last_name,
                'message' => $data["message"],
                'is_admin' => $data["is_admin"],
            ]);
            $message->date = date('H:i M d',strtotime($message->created_at));
           
            $pusher = new Pusher;
            $pusher->sendMessage($room, [
                "message" => $message->message,
                "author" => $message->author,
                "is_admin" => $message->is_admin,
                "date" => $message->date,
            ]);
            return response()->json($message);
        }
    }

    public function listMessages() {
        $room = ChatRoom::where('user_id', auth()->user()->id)->first();
        $messages = ChatMessage::where('chat_room_id', $room->id)->get();
        return response()->json($messages);
    }
}

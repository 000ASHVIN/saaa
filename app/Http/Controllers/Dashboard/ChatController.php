<?php

namespace App\Http\Controllers\Dashboard;

use App\Chats\ChatMessage;
use App\Chats\ChatRoom;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Input;
use App\Pusher;
use Request;

class ChatController extends Controller
{
    public function saveMessage(Request $request)
    {
        if(Request::ajax()) {
            $data = Input::all();
            $user = auth()->user();
            $room = ChatRoom::find($data['chat_room_id']);
            $new_room = 'no';
            if($room->is_ended == '1') {
                $room = ChatRoom::create([
                    'user_id' => $user->id,
                    'name' => $user->first_name." ".$user->last_name
                ]);
                $new_room = 'yes';
            }
            $message = ChatMessage::create([
                "chat_room_id" => $room->id,
                "user_id" => $user->id,
                "author" => $user->first_name." ".$user->last_name,
                "message" => $data["message"],
                "is_admin" => 0,
            ]);
            $message->date = date('H:i M d',strtotime($message->created_at));
            
            $pusher = new Pusher;
            $pusher->sendMessage($room, [
                "message" => $message->message,
                "author" => $message->author,
                "is_admin" => $message->is_admin,
                "date" => $message->date,
            ]);
            return response()->json(['message' => $message, 'room' => $room, 'new_room' => $new_room]);
        }
    }

    public function listMessages() {
        $messages = [];
        if(auth()->user()) {
            $room = ChatRoom::where('user_id', auth()->user()->id)->first();
            if($room) {
                $messages = ChatMessage::where('chat_room_id', $room->id)->get();
                foreach($messages as $message) {
                    $message->date = date('H:i M d',strtotime($message->created_at));
                }
            }
        }
        return response()->json($messages);
    }

    public function createChatRoom() {
        $user = auth()->user();
        ChatRoom::where('user_id', $user->id)->where('is_ended', false)->update(['is_ended' => 1]);
        $room = ChatRoom::create([
            'user_id' => $user->id,
            'name' => $user->first_name." ".$user->last_name
        ]);

        return response()->json(["chat_room_id" => $room->id]);
    }

    public function endChatRoom(Request $request, $roomId = '') {
        $user = auth()->user();
        if($roomId != '') {
            $room = ChatRoom::find($roomId);
            $room->update(['is_ended' => 1]);
        } else {
            $activeRooms = ChatRoom::where('user_id', $user->id)->where('is_ended', false)->get();
            if(count($activeRooms) > 0)
                ChatRoom::where('user_id', $user->id)->where('is_ended', false)->update(['is_ended' => 1]);
        }
        
        return response()->json(["success" => "success"]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use GeneralTrait;

     public function createConversation(Request $request)
{
   try{
    $conversationData = $request->all();

    $conversation = Conversation::create([
        'type' => $conversationData['type'],
        'name' => $conversationData['name']
    ]);

    ConversationParticipant::create([
        'conversation_id' => $conversation->id,
        'user_id' => $conversationData['user_id'],
        'user_type' => $conversationData['user_type']
    ]);

    return $this->successResponse(['message' => 'تم إنشاء المحادثة بنجاح']);
        } 
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

}

public function sendMessage(Request $request)
{
    try {
    $messageData = $request->all();

    $message = Message::create([
        'conversation_id' => $messageData['conversation_id'],
        'sender_id' => $messageData['sender_id'],
        'sender_type' => $messageData['sender_type'],
        'message' => $messageData['message'],
        'file_url' => $messageData['file_url'],
        'created_at' => Carbon::now(),
    ]);
    return $this->successResponse(['message' => 'تم إرسال الرسالة بنجاح']);
} 
catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
}
}


public function loadMessages(Request $request)
{
    try {
    $conversationId = $request->conversation_id;
    $messages = Message::where('conversation_id', $conversationId)
                        ->orderBy('created_at', 'desc')
                        ->take(10) // احتمالية تحميل 10 رسائل
                        ->get();

    return $this->successResponse($messages);
} 
catch (\Exception $ex) {
    return $this->errorResponse($ex->getMessage(), 500);
}
}



    public function updateUserLastSeen(Request $request)
{
    $userId = $request->user_id;
    $user = User::find($userId);

    if ($user) {
        $lastSeen = Carbon::now()->subMinutes(); // تخمين وقت آخر ظهور منذ خمس دقائق

        // قم بتعديل الوقت حسب توقيت آخر ظهور الفعلي للمستخدم
        // على سبيل المثال: $lastSeen = Carbon::parse($user->last_seen); // حسب وقت آخر ظهور الفعلي

        $formattedLastSeen = $lastSeen->diffForHumans(); // تنسيق الوقت بشكل "منذ خمس دقائق"، "منذ ساعة"، إلخ
        
        return response()->json($formattedLastSeen);
    } else {
        return response()->json(['error' => 'المستخدم غير موجود'], 404);
    }
}

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}

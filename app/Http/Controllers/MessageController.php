<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // 🔥 ABRIR CHAT
    public function chat(User $user)
    {
        return view('chat.index', compact('user'));
    }

    // 🔥 BUSCAR MENSAGENS (AJAX)
    public function getMessages(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('from_id', auth()->id())
              ->where('to_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('from_id', $user->id)
              ->where('to_id', auth()->id());
        })
        ->orderBy('created_at')
        ->get();

        return response()->json($messages);
    }

    // 🔥 ENVIAR MENSAGEM (SEM RELOAD)
    public function send(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|max:500'
        ]);

        Message::create([
            'from_id' => auth()->id(),
            'to_id' => $user->id,
            'message' => $request->message
        ]);

        return response()->json([
            'status' => 'ok'
        ]);
    }

    // 📩 LISTA DE CONVERSAS (INBOX)
    public function inbox()
    {
        $userId = auth()->id();

        $conversas = Message::selectRaw('
                IF(from_id = ?, to_id, from_id) as user_id,
                MAX(created_at) as last_message_time
            ', [$userId])
            ->where('from_id', $userId)
            ->orWhere('to_id', $userId)
            ->groupBy('user_id')
            ->orderByDesc('last_message_time')
            ->get();

        $users = User::whereIn('id', $conversas->pluck('user_id'))->get();

        return view('chat.inbox', compact('users'));
    }

    // 🔔 NOTIFICAÇÕES
    public function notificacoes()
    {
        $count = Message::where('to_id', auth()->id())
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }
}

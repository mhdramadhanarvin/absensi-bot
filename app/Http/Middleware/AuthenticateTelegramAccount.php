<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UsersModel;

class AuthenticateTelegramAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $fromTelegram = $request->message['chat'];
        $user = UsersModel::find($fromTelegram['id']);
        $name = $fromTelegram['username'];

        if (!$user) {
            $users = new UsersModel;
            $users->id = $fromTelegram['id'];
            $users->name = $name;
            $users->save();
        }

        return $next($request);
    }
}

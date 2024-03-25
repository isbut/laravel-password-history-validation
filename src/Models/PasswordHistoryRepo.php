<?php

namespace Infinitypaul\LaravelPasswordHistoryValidation\Models;
use App\User;

class PasswordHistoryRepo
{
    /**
     * @param $password
     * @param $user_id
     */
    public static function storeCurrentPasswordInHistory($password, $user_id)
    {
			// Borrado historial
			$num = PasswordHistory::where('user_id', $user_id)->count();
			if ($num >= config('password-history.keep')) {
				PasswordHistory::where('user_id', $user_id)->orderBy('created_at', 'asc')->first()->delete();
			}
			// Creación del registro
			PasswordHistory::create(get_defined_vars());
    }

    /**
     * @param $user
     * @param $checkPrevious
     * @return mixed
     */
    public static function fetchUser($user, $checkPrevious)
    {
        return PasswordHistory::where('user_id', $user->id)->latest()->take($checkPrevious)->get();
    }
}

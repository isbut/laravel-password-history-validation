<?php

namespace Infinitypaul\LaravelPasswordHistoryValidation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Infinitypaul\LaravelPasswordHistoryValidation\Models\PasswordHistoryRepo;
use App\User;

class NotFromPasswordHistory implements Rule
{
    protected $email;
    protected $checkPrevious;

    /**
     * NotFromPasswordHistory constructor.
     *
     * @param $user
     */
    public function __construct($email)
    {
        $this->email = $email;
        $this->checkPrevious = config('password-history.keep');
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
			
			$user = User::where('email', $this->email)->first();
			if ($user) {
				$passwordHistories = PasswordHistoryRepo::fetchUser($user, $this->checkPrevious);
				foreach ($passwordHistories as $passwordHistory) {
					if (Hash::check($value, $passwordHistory->password)) {
						return false;
					}
				}
			} else {
				return false;
			}

			return true;
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
			return __('passwords.history') == 'passwords.history' ? 'The Password Has Been Used' : __('passwords.history');
    }
}

<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ActivateController extends Controller
{
    /**
     * @var string
     */
    private $code;

    public function activateAccount($code)
    {
        $this->code = $code;

        return $this->checkCode();
    }

    private function checkCode()
    {
        $user = User::where('code_confirm', $this->code)
            ->firstOrFail();

        if ( ! $user) {
            return 'Not correct code';
        }

        if ($user->code_confirm === $this->code) {
            $user->confirmed = 1;
            $user->code_confirm = null;
            $user->save();

            return 'true';
        }

        return false;
    }
}

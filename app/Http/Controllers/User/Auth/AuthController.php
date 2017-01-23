<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Email;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getRegister()
    {
        return view('user.auth.register');
    }

    public function postRegister( Request $request )
    {
        $validator = Validator::make($request->all(), [
                'user.email' => 'required|min:6|max:30|unique:users,email',
                'user.password' => 'required|min:4|max:30',
                'user.login' => 'required|min:4|max:25',
        ]);

        if ( $validator->fails() ) {
            return response()->json($validator->messages(), 200);
        }

        if ( $request->input('user.password') !== $request->input('user.passwordConfirmation') ) {
            return response()->json(['error' => 'Пароли не совпадают'], 200);
        }

        // Письмо для подтверждения аккаунта
        $type = 'view';
        $view = 'emails.confirm_account';
        $to = $request->input('user.email');
        $code_confirm = generateKey();

        (new Email)->newEmail($type, $view, $to, '', $code_confirm);

        $user = new User;

        $user->email = $request->input('user.email');
        $user->password = bcrypt($request->input('user.password'));
        $user->code_confirm = $code_confirm;

        $user->save();

        /**
         * В таблицу 'users' записывается только логин и пароль
         * Остальная информация попадет в другую таблицу ('user_info')
         */
        $userNameForAvatar = (new UserInfo)->newInfo($user->id);

        if ( request()->hasFile('image') ) {
            // Метод для изменения имени загруженной аватарки в базе данных
            (new Image)->renameAvatar($userNameForAvatar, $request->input('user.imageId'), true, $user->id);

            // Метод для изменения имени загружнной аватрки в папке
            (new Image)->renameAvatarDir($request->input('user.imageName'), $userNameForAvatar, 'users');
        }

        return response()->json(['success' => 'Спасибо за регистрацию']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAuth( Request $request )
    {
        $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
        ]);

        if ( $validator->fails() ) {
            return response()->json($validator->messages(), 200);
        }

        if ( \Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) ) {
            return response()->json(['success' => 'Успех']);
        } else {
            return response()->json(['error' => 'Неверно веденные данные.']);
        }
    }
}
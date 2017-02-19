<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Email;
use App\Models\User;
use App\Models\UserInfo;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getRegister()
    {
        return view('user.auth.register');
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'user.email' => 'required|min:6|max:30|unique:users,email',
                'user.password' => 'required|min:4|max:30',
                'user.login' => 'required|min:4|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        if ($request->input('user.password') !== $request->input('user.passwordConfirmation')) {
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

        if (request()->input('imageUploaded') == true) {
            foreach ( request()->input('imageResponse') as $key => $value ) {
                $userNameForAvatarMime = $userNameForAvatar . '.' . explode('/', $value['imageType'])[1];
                // Метод для изменения имени загруженной аватарки в базе данных
                (new Image)->renameAvatar($userNameForAvatarMime, $value['imageId'], true, $user->id);

                // Метод для изменения имени загружнной аватрки в папке
                // Юзаю explode, ибо я дурачек, который передает images/user, а в этом методе это дописывается...
                (new Image)->renameAvatarDir(explode('/', $value['imageName'])[3], $userNameForAvatarMime, 'user');
            }
        } else {
            $userDefaultImage = (new Image)->createNewDefaultImage('users');

            // Метод для изменения имени загруженной аватарки в базе данных
            (new Image)->renameAvatar('default.jpg', $userDefaultImage->id, true, $user->id);
        }

        return response()->json(['success' => 'Спасибо за регистрацию']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAuth(Request $request)
    {
        if ( \Auth::check() ) {
            return response()->json(['error' => 'Вы уже авторизованы'], 402);
        }

        $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $user = User::where('email', $request->input('email'))->first();

        if ( !$user ) {
            return response()->json(['error' => 'We does`nt have this email'], 406);
        }

        if ( $user->confirmed == 0 ) {
            return response()->json(['error' => 'Вы не активировали аккаунт'], 401);
        }

        if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return response()->json('Вы успешно авторизованы', 200);
        } else {
            return response()->json(['error' => 'Неверно веденные данные.'], 406);
        }
    }
}
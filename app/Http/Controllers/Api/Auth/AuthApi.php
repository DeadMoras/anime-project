<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Controller;
use App\TokenService\Components\Token;
use App\TokenService\TokenService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function postAuth(Request $request)
    {
        return (new TokenService(new Token))->getAuth($request->input('data'));
    }

    public function postRegister(Request $request)
    {
        $error = new Errors;

        $validator = Validator::make(
            $request->all(), [
            'user.email'    => 'required|min:6|max:30|unique:users,email',
            'user.password' => 'required|min:4|max:30',
            'user.login'    => 'required|min:4|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject('error_data', $validator->messages()), 406);
        }

        if ($request->input('user.password') !== $request->input('user.passwordConfirmation')) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addOjbect('error_data', 'Пароли не совпадают'), 406);
        }
        if ( ! is_array(request()->input('imageUploaded'))) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject(
                        'error_data', 'Неверный формат картинки. Картинки должны быть в массиве')
                    ->getResponse(), 406);
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
            foreach (request()->input('imageResponse') as $key => $value) {
                $userNameForAvatarMime = $userNameForAvatar.'.'.explode(
                        '/', $value['imageType'])[1];

                // Метод для изменения имени загруженной аватарки в базе данных
                (new Image)->renameAvatar(
                    $userNameForAvatarMime, $value['imageId'], true, $user->id);

                // Метод для изменения имени загружнной аватрки в папке
                // Юзаю explode, ибо я дурачек, который передает images/user, а в этом методе это дописывается...
                (new Image)->renameAvatarDir(
                    explode(
                        '/', $value['imageName'])[3], $userNameForAvatarMime, 'user');
            }
        } else {
            $userDefaultImage = (new Image)->createNewDefaultImage('users');

            // Метод для изменения имени загруженной аватарки в базе данных
            (new Image)->renameAvatar(
                'default.jpg', $userDefaultImage->id, true, $user->id);
        }

        return response()->json(
            $error->addObject(
                'response', 'Спасибо за регистрацию')
                ->getResponse(), 200);
    }
}
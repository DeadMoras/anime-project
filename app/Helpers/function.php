<?php

function generateKey($length = 10, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
{
    $str = '';
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count - 1)];
    }

    return $str;
}

function rus2translit($string)
{
    $converter = [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '\'',
            'ы' => 'y',
            'ъ' => '\'',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',

            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ь' => '\'',
            'Ы' => 'Y',
            'Ъ' => '\'',
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
    ];

    return strtr($string, $converter);
}

function strOther($str, $type)
{
    $str = rus2translit($str);

    $str = strtolower($str);

    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);

    $str = trim($str, "-");

    if ( $type == 'image' ) {
        $newStr = checkStrImage($str);
    } elseif ( $type == 'seo' ) {
        $newStr = checkStr($str);
    } else {
        $newStr = $str;
    }

    return $newStr;
}

function checkStr($str)
{
    $data = \App\Models\Seo::pluck('path', 'id')
            ->toArray();

    $path = $str;

    if ($data) {
        if (in_array($path, $data)) {
            for ($i = 0; $i < 100; $i++) {
                $path = "{$str}-{$i}";
                if (!in_array($path, $data)) {
                    break;
                }
            }
        }
    }

    return $path;
}

function checkStrImage($str)
{
    $data = \App\Models\Image::pluck('name', 'id')
            ->toArray();

    $path = $str;

    if ($data) {
        if (in_array($path, $data)) {
            for ($i = 0; $i < 100; $i++) {
                $path = "{$str}-{$i}";
                if (!in_array($path, $data)) {
                    break;
                }
            }
        }
    }

    return $path;
}

function userInfo($method)
{
    return (new \App\Http\Controllers\UserInfoController)->{$method}();
}

function deleteVideoFromStorage($path)
{
    \Illuminate\Support\Facades\Storage::delete($path);
}
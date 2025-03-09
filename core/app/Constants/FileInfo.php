<?php

namespace App\Constants;

class FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo()
    {
        $data['withdrawVerify'] = [
            'path' => 'assets/images/verify/withdraw'
        ];
        $data['depositVerify'] = [
            'path'      => 'assets/images/verify/deposit'
        ];
        $data['verify'] = [
            'path'      => 'assets/verify'
        ];
        $data['default'] = [
            'path'      => 'assets/images/default.png',
        ];
        $data['withdrawMethod'] = [
            'path'      => 'assets/images/withdraw/method',
            'size'      => '800x800',
        ];
        $data['ticket'] = [
            'path'      => 'assets/support',
        ];
        $data['logoIcon'] = [
            'path'      => 'assets/images/logoIcon',
        ];
        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['preloader'] = [
            'size' => '55x55',
        ];
        $data['cover'] = [
            'size' => '1830x400',
        ];
        $data['extensions'] = [
            'path'      => 'assets/images/extensions',
            'size'      => '36x36',
        ];
        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];
        $data['flag'] = [
            'path' => 'assets/images/flag',
            'size' => '100x100',
        ];
        $data['adminProfile'] = [
            'path'      => 'assets/admin/images/profile',
            'size'      => '400x400',
        ];
        $data['userProfile'] = [
            'path'      => 'assets/images/user/profile',
            'size'      => '150x150',
        ];
        $data['profileCover'] = [
            'path'      => 'assets/images/user/profile',
            'size'      => '1830x400',
        ];
        $data['gallery'] = [
            'path'      => 'assets/images/gallery',
            'size'      => '630x350',
        ];
        $data['membershipLevel'] = [
            'path'      => 'assets/images/level',
            'size'      => '410x150',
        ];
        $data['maintenance'] = [
            'path' => 'assets/images/frontend/maintenance',
            'size' => '700x400',
        ];

        return $data;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected static $status = [
        'SUKSES' => '00',
        'GAGAL' => '01',
        'PENDING' => '02',
        'NOT_FOUND' => '03',
        'BAD_REQUEST' => '99'
    ];

    protected static $menuBase = [
        [
            'label' => 'Home',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'pi pi-fw pi-home', 'to' => '/'],
            ]
        ],
        [
            'label' => 'User',
            'items' => [
                [
                    'label' => 'User Manager',
                    'to' => '/master/users',
                    'icon' => 'pi pi-users'
                ]
            ]
        ]
    ];
}

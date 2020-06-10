<?php

return [
    [
        'name' => 'Пользователь',
        'permissions' => []
    ],
    [
        'name' => 'Куратор',
        'permissions' => [
            'tests.add',
            'tests.edit'
        ]
    ],
    [
        'name' => 'Администратор',
        'permissions' => [
            'tests.add',
            'tests.edit',
            'tests.delete',
            'documents.add',
            'documents.delete',
            'users.manage',
        ]
    ]
];

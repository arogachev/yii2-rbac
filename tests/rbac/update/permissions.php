<?php

return [
    [
        'name' => 'users.manage',
        'description' => 'Users management',
    ],
    [
        'name' => 'users.avatar.upload',
        'description' => 'Upload avatar for user',
        'rule' => 'arogachev\rbac\rules\CorrespondingUserRule',
    ],
    [
        'name' => 'users.password.change',
        'description' => 'Change password for user',
        'rule' => 'arogachev\rbac\rules\CorrespondingUserRule',
    ],
    [
        'name' => 'users.password.change.all',
        'description' => 'Change password for any user',
    ],
    [
        'name' => 'dispatching-room.access',
        'description' => 'Access to dispatching room',
    ],
    [
        'name' => 'settings.manage',
        'description' => 'Settings management',
    ],
    [
        'name' => 'sessions.access',
        'description' => 'Sessions management',
    ],
];
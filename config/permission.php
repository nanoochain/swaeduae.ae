<?php

return [

    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'acl_roles',
        'permissions' => 'acl_permissions',
        'model_has_permissions' => 'acl_model_has_permissions',
        'model_has_roles' => 'acl_model_has_roles',
        'role_has_permissions' => 'acl_role_has_permissions',
    ],

    'column_names' => [
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    // MUST be a boolean (not an array)
    'teams' => false,

    'register_permission_check_method' => true,

    // keep these top-level
    'display_permission_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];

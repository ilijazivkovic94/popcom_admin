<?php

// Aside menu
return [

    //Admin Menu
    'itemsAdmin' => [
        // Dashboard
        [
            'title' => 'Account List',
            'root' => true,
            'icon' => 'fa fa-user', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'home',
            'new-tab' => false,
        ],
        [
            'title' => 'Global Settings',
            'root' => true,
            'icon' => 'fa fa-cog', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'admin/global-setting',
            'new-tab' => false,
        ],
        [
            'title' => 'Machine Models',
            'root' => true,
            'icon' => 'fa fa-industry', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'admin/machine-model',
            'new-tab' => false,
        ],
        [
            'title' => 'Documentation',
            'root' => true,
            'icon' => 'fa fa-book', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'dlink_admin',
            'new-tab' => true,
        ],
        [
            'title' => 'Logout',
            'root' => true,
            'icon' => 'fa fa-lock', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'logout',
            'new-tab' => false,
        ],
    ],

    //Setting Menu
    'itemsSetting' => [
        // Dashboard
        [
            'title' => 'Home',
            'root' => true,
            'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'home',
            'new-tab' => false,
        ],
        [
            'title' => 'Account',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fas fa-user-cog', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Receipt Settings',
                    'icon' => 'fas fa-envelope', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting/receipt'
                ],
                [
                    'title' => 'Settings',
                    'icon' => 'fa fa-cog', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting'
                ],
                [
                    'title' => 'Account Status',
                    'icon' => 'fab fa-cc-mastercard', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/account-status'
                ]
            ]
        ],
        [
            'title' => 'Documentation',
            'root' => true,
            'icon' => 'fa fa-book', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'dlink',
            'new-tab' => true,
        ],
        [
            'title' => 'Logout',
            'root' => true,
            'icon' => 'fa fa-lock', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'logout',
            'new-tab' => false,
        ],
    ],

    //Account status Menu
    'itemsAccount' => [
        // Dashboard
        [
            'title' => 'Account Status',
            'root' => true,
            'icon' => 'fab fa-cc-mastercard', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'app/account-status',
            'new-tab' => false,
        ],
        [
            'title' => 'Documentation',
            'root' => true,
            'icon' => 'fa fa-book', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'dlink',
            'new-tab' => true,
        ],
        [
            'title' => 'Logout',
            'root' => true,
            'icon' => 'fa fa-lock', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'logout',
            'new-tab' => false,
        ],
    ],

    'itemsStandard' => [
        // Dashboard
        [
            'title' => 'Home',
            'root' => true,
            'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'home',
            'new-tab' => false,
        ],
        [
            'title' => 'Data',
            'icon'  => 'images/home/icon-nav-visitors@1.5x.svg',
            'icon'  => 'fa fa-search-plus', // or can be 'flaticon-home' or any flaticon-*
            'root'  => true,
            'submenu' => [
                [
                    'title' => 'Traffic',
                    'root' => true,
                    'icon' => 'fa fa-walking', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/traffic-analytics',
                    'new-tab' => false,
                ],
                [
                    'title' => 'Visitors',
                    'icon' => 'images/home/icon-nav-visitors@1.5x.svg',
                    //'icon'      => 'fa fa-users', // or can be 'flaticon-home' or any flaticon-*
                    'root' => true,
                    'submenu' => [
                        [
                            'title' => 'Visitors List',
                            'icon' => 'fa fa-users', // or can be 'flaticon-home' or any flaticon-*
                            'page' => 'app/visitors'
                        ],
                        [
                            'title' => 'Visitor Analytics',
                            'icon' => 'fas fa-chart-pie', // or can be 'flaticon-home' or any flaticon-*
                            'page' => 'app/visitors/analytics'
                        ]
                    ]
                ],
                [
                    'title' => 'Sales',
                    'icon' => 'images/home/icon-nav-sales@1.5x.svg',
                    //'icon'      => 'fa fa-shopping-bag', // or can be 'flaticon-home' or any flaticon-*
                    'root' => true,
                    'submenu' => [
                        [
                            'title' => 'All Sales',
                            'icon' => 'fa fa-shopping-bag', // or can be 'flaticon-home' or any flaticon-*
                            'page' => 'app/sales'
                        ],
                        [
                            'title' => 'Sales Analytics',
                            'icon' => 'fas fa-chart-line', // or can be 'flaticon-home' or any flaticon-*
                            'page' => 'app/sales/analytics'
                        ]
                    ]
                ],
                [
                    'title' => 'Customers',
                    'icon' => 'fa fa-user', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/customer'
                ],
            ]
        ],
        [
            'title' => 'Manage',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fa fa-shopping-bag',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Products',
                    'icon' => 'fab fa-codepen', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/products'
                ],
                [
                    'title' => 'Machine Inventory',
                    'icon' => 'fas fa-home', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/machines-inventory'
                ],
                [
                    'title' => 'Promotions',
                    'icon' => 'fas fa-tags', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/promotion'
                ],
                [
                    'title' => 'Advertisements',
                    'icon' => 'fas fa-image', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/advertisement'
                ],
                [
                    'title' => 'Content',
                    'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/content',
                ],
            ]
        ],
        [
            'title' => 'Account',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fas fa-user-cog', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Machines',
                    'icon' => 'fas fa-shopping-cart', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/machines'
                ],
                [
                    'title' => 'Receipt Settings',
                    'icon' => 'fas fa-envelope', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting/receipt'
                ],
                [
                    'title' => 'Settings',
                    'icon' => 'fa fa-cog', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting'
                ],
                [
                    'title' => 'Account Status',
                    'icon' => 'fab fa-cc-mastercard', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/account-status'
                ]
            ]
        ],
        [
            'title' => 'My Account',
            'icon' => 'far fa-user-circle', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Account Details',
                    'icon' => 'far fa-user-circle', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'account'
                ],
                [
                    'title' => 'Logout',
                    'icon' => 'fas fa-lock', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'logout'
                ],
            ]
        ],
        [
            'title' => 'Documentation',
            'root' => true,
            'icon' => 'fa fa-book', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'dlink',
            'new-tab' => true,
        ],
    ],

    'itemsParent' => [
        // Dashboard
        [
            'title' => 'Home',
            'root' => true,
            'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'home',
            'new-tab' => false,
        ],
        [
            'title' => 'Visitors',
            'icon' => 'images/home/icon-nav-visitors@1.5x.svg',
            //'icon'      => 'fa fa-users', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Visitors List',
                    'icon' => 'fa fa-users', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/visitors'
                ],
                [
                    'title' => 'Visitor Analytics',
                    'icon' => 'fas fa-chart-pie', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/visitors/analytics'
                ]
            ]
        ],
        [
            'title' => 'Sales',
            'icon' => 'images/home/icon-nav-sales@1.5x.svg',
            //'icon'      => 'fa fa-shopping-bag', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'All Sales',
                    'icon' => 'fa fa-shopping-bag', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/sales'
                ],
                [
                    'title' => 'Sales Analytics',
                    'icon' => 'fas fa-chart-line', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/sales/analytics'
                ]
            ]
        ],
        [
            'title' => 'Manage',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fa fa-shopping-bag',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Customers',
                    'icon' => 'fa fa-user', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/customer'
                ],
                [
                    'title' => 'Products',
                    'icon' => 'fab fa-codepen', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/products'
                ],
                [
                    'title' => 'Machine Inventory',
                    'icon' => 'fas fa-home', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/machines-inventory'
                ],
                [
                    'title' => 'Advertisements',
                    'icon' => 'fas fa-image', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/advertisement'
                ],
                [
                    'title' => 'Content',
                    'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/content',
                ],
            ]
        ],
        [
            'title' => 'Account',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fas fa-user-cog', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Sub-Accounts',
                    'icon' => 'fas fa-th-list', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/accounts'
                ],
                [
                    'title' => 'Receipt Settings',
                    'icon' => 'fas fa-envelope', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting/receipt'
                ],
                [
                    'title' => 'Company Settings',
                    'icon' => 'fa fa-cog', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting'
                ],
                [
                    'title' => 'Account Status',
                    'icon' => 'fab fa-cc-mastercard', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/account-status'
                ]
            ]
        ],
        [
            'title' => 'My Account',
            'icon' => 'far fa-user-circle', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Account Details',
                    'icon' => 'far fa-user-circle', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'account'
                ],
                [
                    'title' => 'Logout',
                    'icon' => 'fas fa-lock', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'logout'
                ],
            ]
        ],
        [
            'title' => 'Documentation',
            'root' => true,
            'icon' => 'fa fa-book', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'dlink',
            'new-tab' => true,
        ],
    ],

    'itemsSubParent' => [
        // Dashboard
        [
            'title' => 'Home',
            'root' => true,
            'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'home',
            'new-tab' => false,
        ],
        [
            'title' => 'Visitors',
            'icon' => 'images/home/icon-nav-visitors@1.5x.svg',
            //'icon'      => 'fa fa-users', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Visitors List',
                    'icon' => 'fa fa-users', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/visitors'
                ],
                [
                    'title' => 'Visitor Analytics',
                    'icon' => 'fas fa-chart-pie', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/visitors/analytics'
                ]
            ]
        ],
        [
            'title' => 'Sales',
            'icon' => 'images/home/icon-nav-sales@1.5x.svg',
            //'icon'      => 'fa fa-shopping-bag', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'All Sales',
                    'icon' => 'fa fa-shopping-bag', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/sales'
                ],
                [
                    'title' => 'Sales Analytics',
                    'icon' => 'fas fa-chart-line', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/sales/analytics'
                ]
            ]
        ],
        [
            'title' => 'Manage',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fa fa-shopping-bag',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Customers',
                    'icon' => 'fa fa-user', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/customer'
                ],
                [
                    'title' => 'Products',
                    'icon' => 'fab fa-codepen', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/products'
                ],
                [
                    'title' => 'Machine Inventory',
                    'icon' => 'fas fa-home', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/machines-inventory'
                ],
                [
                    'title' => 'Promotions',
                    'icon' => 'fas fa-tags', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/promotion'
                ],
                [
                    'title' => 'Advertisements',
                    'icon' => 'fas fa-image', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/advertisement'
                ],
                [
                    'title' => 'Content',
                    'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/content',
                ],
            ]
        ],
        [
            'title' => 'Account',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'icon' => 'fas fa-user-cog', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Machines',
                    'icon' => 'fas fa-shopping-cart', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/machines'
                ],
                [
                    'title' => 'Receipt Settings',
                    'icon' => 'fas fa-envelope', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting/receipt'
                ],
                [
                    'title' => 'Settings',
                    'icon' => 'fa fa-cog', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/setting'
                ],
                [
                    'title' => 'Account Status',
                    'icon' => 'fab fa-cc-mastercard', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'app/account-status'
                ]
            ]
        ],
        [
            'title' => 'My Account',
            'icon' => 'far fa-user-circle', // or can be 'flaticon-home' or any flaticon-*
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Account Details',
                    'icon' => 'far fa-user-circle', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'account'
                ],
                [
                    'title' => 'Logout',
                    'icon' => 'fas fa-lock', // or can be 'flaticon-home' or any flaticon-*
                    'page' => 'logout'
                ],
            ]
        ],
        [
            'title' => 'Documentation',
            'root' => true,
            'icon' => 'fa fa-book', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'dlink',
            'new-tab' => true,
        ],
    ],

    'items' => [
        // Dashboard
        [
            'title' => 'Account',
            'root' => true,
            'icon' => 'fa fa-home', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/home',
            'new-tab' => false,
        ],

        [
            'title' => 'Global Settings',
            'root' => true,
            'icon' => 'fa fa-cog', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/users',
            'new-tab' => false,
        ],

        [
            'title' => 'Machine Models',
            'root' => true,
            'icon' => 'fa fa-industry', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/categories',
            'new-tab' => false,
        ],

        //  [
        //     'title' => 'Logout',
        //     'root' => true,
        //     'icon' => 'flaticon-lock', // or can be 'flaticon-home' or any flaticon-*
        //     'page' => '/logout',
        //     'new-tab' => false,
        // ],

        // // Custom
        // [
        //     'section' => 'Custom',
        // ],
        // [
        //     'title' => 'Applications',
        //     'icon' => 'media/svg/icons/Layout/Layout-4-blocks.svg',
        //     'bullet' => 'line',
        //     'root' => true,
        //     'submenu' => [
        //         [
        //             'title' => 'Users',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'List - Default',
        //                     'page' => 'test',
        //                 ],
        //                 [
        //                     'title' => 'List - Datatable',
        //                     'page' => 'custom/apps/user/list-datatable'
        //                 ],
        //                 [
        //                     'title' => 'List - Columns 1',
        //                     'page' => 'custom/apps/user/list-columns-1'
        //                 ],
        //                 [
        //                     'title' => 'List - Columns 2',
        //                     'page' => 'custom/apps/user/list-columns-2'
        //                 ],
        //                 [
        //                     'title' => 'Add User',
        //                     'page' => 'custom/apps/user/add-user'
        //                 ],
        //                 [
        //                     'title' => 'Edit User',
        //                     'page' => 'custom/apps/user/edit-user'
        //                 ],
        //             ]
        //         ],
        //         [
        //             'title' => 'Profile',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Profile 1',
        //                     'bullet' => 'line',
        //                     'submenu' => [
        //                         [
        //                             'title' => 'Overview',
        //                             'page' => 'custom/apps/profile/profile-1/overview'
        //                         ],
        //                         [
        //                             'title' => 'Personal Information',
        //                             'page' => 'custom/apps/profile/profile-1/personal-information'
        //                         ],
        //                         [
        //                             'title' => 'Account Information',
        //                             'page' => 'custom/apps/profile/profile-1/account-information'
        //                         ],
        //                         [
        //                             'title' => 'Change Password',
        //                             'page' => 'custom/apps/profile/profile-1/change-password'
        //                         ],
        //                         [
        //                             'title' => 'Email Settings',
        //                             'page' => 'custom/apps/profile/profile-1/email-settings'
        //                         ]
        //                     ]
        //                 ],
        //                 [
        //                     'title' => 'Profile 2',
        //                     'page' => 'custom/apps/profile/profile-2'
        //                 ],
        //                 [
        //                     'title' => 'Profile 3',
        //                     'page' => 'custom/apps/profile/profile-3'
        //                 ],
        //                 [
        //                     'title' => 'Profile 4',
        //                     'page' => 'custom/apps/profile/profile-4'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Contacts',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'List - Columns',
        //                     'page' => 'custom/apps/contacts/list-columns'
        //                 ],
        //                 [
        //                     'title' => 'List - Datatable',
        //                     'page' => 'custom/apps/contacts/list-datatable'
        //                 ],
        //                 [
        //                     'title' => 'View Contact',
        //                     'page' => 'custom/apps/contacts/view-contact'
        //                 ],
        //                 [
        //                     'title' => 'Add Contact',
        //                     'page' => 'custom/apps/contacts/add-contact'
        //                 ],
        //                 [
        //                     'title' => 'Edit Contact',
        //                     'page' => 'custom/apps/contacts/edit-contact'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Projects',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'List - Columns 1',
        //                     'page' => 'custom/apps/projects/list-columns-1'
        //                 ],
        //                 [
        //                     'title' => 'List - Columns 2',
        //                     'page' => 'custom/apps/projects/list-columns-2'
        //                 ],
        //                 [
        //                     'title' => 'List - Columns 3',
        //                     'page' => 'custom/apps/projects/list-columns-3'
        //                 ],
        //                 [
        //                     'title' => 'List - Columns 4',
        //                     'page' => 'custom/apps/projects/list-columns-4'
        //                 ],
        //                 [
        //                     'title' => 'List - Datatable',
        //                     'page' => 'custom/apps/projects/list-datatable'
        //                 ],
        //                 [
        //                     'title' => 'View Project',
        //                     'page' => 'custom/apps/projects/view-project'
        //                 ],
        //                 [
        //                     'title' => 'Add Project',
        //                     'page' => 'custom/apps/projects/add-project'
        //                 ],
        //                 [
        //                     'title' => 'Edit Project',
        //                     'page' => 'custom/apps/projects/edit-project'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Support Center',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Home 1',
        //                     'page' => 'custom/apps/support-center/home-1'
        //                 ],
        //                 [
        //                     'title' => 'Home 2',
        //                     'page' => 'custom/apps/support-center/home-2'
        //                 ],
        //                 [
        //                     'title' => 'FAQ 1',
        //                     'page' => 'custom/apps/support-center/faq-1'
        //                 ],
        //                 [
        //                     'title' => 'FAQ 2',
        //                     'page' => 'custom/apps/support-center/faq-2'
        //                 ],
        //                 [
        //                     'title' => 'FAQ 3',
        //                     'page' => 'custom/apps/support-center/faq-3'
        //                 ],
        //                 [
        //                     'title' => 'Feedback',
        //                     'page' => 'custom/apps/support-center/feedback'
        //                 ],
        //                 [
        //                     'title' => 'License',
        //                     'page' => 'custom/apps/support-center/license'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Chat',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Private',
        //                     'page' => 'custom/apps/chat/private'
        //                 ],
        //                 [
        //                     'title' => 'Group',
        //                     'page' => 'custom/apps/chat/group'
        //                 ],
        //                 [
        //                     'title' => 'Popup',
        //                     'page' => 'custom/apps/chat/popup'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Todo',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Tasks',
        //                     'page' => 'custom/apps/todo/tasks'
        //                 ],
        //                 [
        //                     'title' => 'Docs',
        //                     'page' => 'custom/apps/todo/docs'
        //                 ],
        //                 [
        //                     'title' => 'Files',
        //                     'page' => 'custom/apps/todo/files'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Inbox',
        //             'bullet' => 'dot',
        //             'page' => 'custom/apps/inbox',
        //             'label' => [
        //                 'type' => 'label-danger label-inline',
        //                 'value' => 'new'
        //             ]
        //         ]
        //     ]
        // ],
        // [
        //     'title' => 'Pages',
        //     'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
        //     'bullet' => 'dot',
        //     'root' => true,
        //     'submenu' => [
        //         [
        //             'title' => 'Wizard',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Wizard 1',
        //                     'page' => 'custom/pages/wizard/wizard-1'
        //                 ],
        //                 [
        //                     'title' => 'Wizard 2',
        //                     'page' => 'custom/pages/wizard/wizard-2'
        //                 ],
        //                 [
        //                     'title' => 'Wizard 3',
        //                     'page' => 'custom/pages/wizard/wizard-3'
        //                 ],
        //                 [
        //                     'title' => 'Wizard 4',
        //                     'page' => 'custom/pages/wizard/wizard-4'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Pricing Tables',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Pricing Tables 1',
        //                     'page' => 'custom/pages/pricing/pricing-1'
        //                 ],
        //                 [
        //                     'title' => 'Pricing Tables 2',
        //                     'page' => 'custom/pages/pricing/pricing-2'
        //                 ],
        //                 [
        //                     'title' => 'Pricing Tables 3',
        //                     'page' => 'custom/pages/pricing/pricing-3'
        //                 ],
        //                 [
        //                     'title' => 'Pricing Tables 4',
        //                     'page' => 'custom/pages/pricing/pricing-4'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Invoices',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Invoice 1',
        //                     'page' => 'custom/pages/invoices/invoice-1'
        //                 ],
        //                 [
        //                     'title' => 'Invoice 2',
        //                     'page' => 'custom/pages/invoices/invoice-2'
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'User Pages',
        //             'bullet' => 'dot',
        //             'label' => [
        //                 'type' => 'label-rounded label-primary',
        //                 'value' => '2'
        //             ],
        //             'submenu' => [
        //                 [
        //                     'title' => 'Login 1',
        //                     'page' => 'custom/pages/users/login-1',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Login 2',
        //                     'page' => 'custom/pages/users/login-2',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Login 3',
        //                     'page' => 'custom/pages/users/login-3',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Login 4',
        //                     'page' => 'custom/pages/users/login-4',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Login 5',
        //                     'page' => 'custom/pages/users/login-5',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Login 6',
        //                     'page' => 'custom/pages/users/login-6',
        //                     'new-tab' => true
        //                 ]
        //             ]
        //         ],
        //         [
        //             'title' => 'Error Pages',
        //             'bullet' => 'dot',
        //             'submenu' => [
        //                 [
        //                     'title' => 'Error 1',
        //                     'page' => 'custom/pages/errors/error-1',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Error 2',
        //                     'page' => 'custom/pages/errors/error-2',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Error 3',
        //                     'page' => 'custom/pages/errors/error-3',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Error 4',
        //                     'page' => 'custom/pages/errors/error-4',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Error 5',
        //                     'page' => 'custom/pages/errors/error-5',
        //                     'new-tab' => true
        //                 ],
        //                 [
        //                     'title' => 'Error 6',
        //                     'page' => 'custom/pages/errors/error-6',
        //                     'new-tab' => true
        //                 ]
        //             ]
        //         ]
        //     ]
        // ],

    ]

];

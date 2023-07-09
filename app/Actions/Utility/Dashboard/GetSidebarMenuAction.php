<?php

namespace App\Actions\Utility\Dashboard;

class GetSidebarMenuAction
{
    public function handle()
    {
        return [
            [
                'text' => 'Dashboard',
                'url'  => route('dashboard.index'),
                'icon' => 'VDashboard',
                'can'  => 'view_general_dashboard'
            ],
            [
                'text' => 'Recapitulation',
                'url' => route('recapitulation.index'),
                'icon' => "VChartSimple",
            ],
            [
                'text' => 'Candidate',
                'url' => route('candidates.index'),
                'icon' => 'VPerson',
                'can' => ['view_candidate']
            ],
            [
                'text' => 'Candidate Pairs',
                'url' => route('candidate-pairs.index'),
                'icon' => 'VUserGroup',
                // 'can' => ['view_candidate']
            ],
            [
                'text' => 'Voters',
                'icon' => 'VUser',
                'group' => true,
                'submenu' => [
                    [
                        'text' => 'Student',
                        'url' => route('voters.students.index'),
                    ],
                    [
                        'text' => 'Teacher',
                        'url' => route('voters.teachers.index'),
                    ]
                ]
            ],
            [
                'text' => 'Settings',
                'icon' => 'VSetting',
                'group' => true,
                'can'  => ['view_systems_role_management'],
                'submenu' => [
                    [
                        'text' => 'Role Management',
                        'url'  => route('settings.role.index'),
                        'can'  => 'view_systems_role_management',
                    ],
                ],
            ],
        ];
    }
}

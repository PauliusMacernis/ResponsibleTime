<?php
declare(strict_types=1);

namespace ResponsibleTime;

class SettingsProject
{
    /** @see https://regex101.com/ */
    public const PROJECTS_WE_TRACK_TIME_ON = [

        // ------------ Something random
        'Random' => [
            'on' => [
                'POWER IS OFF' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/.*POWER IS OFF.*/'
                ]
            ]
        ],

        // ------------- JIRA-based------------
        'Gresv PIM' => [
            'on' => [
                'Any AP-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WAP-[0-9]+\W/'
                ]
            ]
        ],
        'Akeneo Marketplace: IntPl (PHP)' => [
            'on' => [
                'Any WB-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WWB-96\W/'
                ]
            ]
        ],
        'Akeneo Marketplace, except IntPl (PHP)' => [
            'on' => [
                'Any WB-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/(?!\WWB-96\W)\WWB-[0-9]+\W/'
                ]
            ]
        ],
        'Akeneo Deploy' => [
            'on' => [
                'Any AD-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WAD-[0-9]+\W/'
                ]
            ]
        ],
        'Em. Devs' => [
            'on' => [
                'Any ED-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WED-[0-9]+\W/'
                ]
            ]
        ],
        'Integration-platform' => [
            'on' => [
                'Any IP-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WIP-[0-9]+\W/'
                ]
            ]
        ],
        'Libra' => [
            'on' => [
                'Any LIBRA-* ticket activity' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WLIBRA-[0-9]+\W/'
                ]
            ]
        ],


        // ----------- PERSONAL ----------------------------------------------------------------------------------------

        'Personal projects on Github: Responsible Time' => [
            'on' => [

                // ------------- Github-based------------
                'Any project on personal Github' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WPauliusMacernis\/ResponsibleTime\W/'
                ],
                'Any project on personal Github (old)' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\Wsugalvojau\/Responsible-time\W/'
                ],

                // -------------- File-based
                'Any project in ~/dev/ResponsibleTime' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\W~\/dev\/ResponsibleTime\W/'
                ],
                'Any project in ~/dev/Responsible-time (old)' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\W~\/dev\/Responsible-time\W/'
                ],
            ]
        ],

        'Personal projects on Github: other' => [
            'on' => [
                'Any project on personal Github' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\WPauliusMacernis\W/'
                ],
                'Any project on personal Github (old)' => [
                    'WmClass' => '/.*/',
                    'WindowTitle' => '/\Wsugalvojau\W/'
                ],
            ]
        ],
    ];
}

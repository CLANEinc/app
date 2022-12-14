<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Rankingmanual4;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getNav()
    {
        return [
            'content' => [
                'children' => [
                    'plugin_rankingmanual' => [
                        'name' => 'plugin_rankingmanual.admin.navi',
                        'url' => 'plugin_rankingmanual_list',
                    ],
                ],
            ],
        ];
    }
}

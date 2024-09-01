<?php

namespace Tests;

use DDDCore\VO\MainVo;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{

    public function testExample(): void
    {
        $list = [
            [
                'id'        => 1,
                'name'      => '1111',
                'age'       => '111',
                'parent_id' => 0,
                'role'      => [
                    'key'   => '1111',
                    'value' => '111',
                    'age'   => '1'
                ],
                'menu'      => [
                    [
                        'id'   => 1,
                        'name' => '售票员也'
                    ],
                    [
                        'id'   => 2,
                        'name' => '售票员也'
                    ]
                ]
            ],
            [
                'id'        => 2,
                'name'      => '2222',
                'age'       => '222',
                'parent_id' => 0,
                'role'      => [
                    'key'   => '2222',
                    'value' => '222',
                    'age'   => '2'
                ]
            ],
            [
                'id'        => 3,
                'name'      => '3333',
                'age'       => '333',
                'parent_id' => 0,
                'role'      => [
                    'key'   => '3333',
                    'value' => '333',
                    'age'   => '3'
                ]
            ]
        ];

        $mainVo = new MainVo();
        try {
            $res = $mainVo->toResult($list);
            print_r($res);
        }catch (\Exception $e) {
            print_r($e->getCode());
        }

        $this->assertTrue(true);
    }
}

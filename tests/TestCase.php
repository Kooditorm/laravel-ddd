<?php

namespace Tests;


use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected array $headers = [
        "Accept" => "application/psr.ant.v1+json",
        "Content-Type" => "application/json",
        "Authorization" => ""
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpBaseUrl();
    }


    public function setUpBaseUrl(): void
    {

        if ($domain = config('api.domain')) {
            config(['app.url' => 'https://'.$domain]);
        }
    }
}

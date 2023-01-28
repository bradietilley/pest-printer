<?php

use BradieTilley\PestPrinter\Config;
use Tests\TestCase;

uses(TestCase::class)->in('Unit', 'Feature');

beforeEach(function () {
    Config::flush();
});

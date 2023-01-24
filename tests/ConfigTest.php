<?php

use BradieTilley\PestPrinter\Config;

beforeEach(function () {
    Config::flush();
});

test('config can fetch settings via dot notation', function () {
    $value = Config::get('statuses.pending.present', 'default');

    expect($value)->toBe('Pending');
});

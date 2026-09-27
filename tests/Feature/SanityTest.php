<?php

test('queue connection é sync durante os testes', function () {
    expect(config('queue.default'))->toBe('sync');
});

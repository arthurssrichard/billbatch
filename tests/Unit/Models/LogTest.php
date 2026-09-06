<?php

use App\Enums\LogStatus;
use App\Models\Log;

test('status é do tipo enum', function () {
    $log = Log::factory()->create();
    expect($log->status)->toBeInstanceOf(LogStatus::class);
});

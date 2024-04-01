<?php

namespace Tests\Unit;

use App\Models\Post;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function test_is_published_condition(): void
    {
        Carbon::setTestNow('2024-04-01');
        $now = Carbon::now()->format('Y-m-d');
        $postSql = Post::query()->toRawSql();
        $needle = "date(`publish_at`) <= '$now'";
        $this->assertStringContainsString($needle, $postSql);
    }
}

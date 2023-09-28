<?php

namespace Macino\tests;

use Macino\CliDumper\CliFormat;
use PHPUnit\Framework\TestCase;

class CliFormatTest extends TestCase
{
    public function testFormat(): void
    {
        $this->assertEquals("\e[31mtest\e[0m", CliFormat::format('test', CliFormat::FG_RED), 'single format');
    }
}

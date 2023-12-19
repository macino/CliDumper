<?php

use PHPUnit\Framework\TestCase;
use Macino\CliDumper\CliDumper;

class CliDumperTest extends TestCase
{
    private CliDumper $dumper;
    private function dump(string $message, mixed $var): string
    {
        if (!isset($this->dumper)) {
            $this->dumper = new Macino\CliDumper\CliDumper();
            $this->dumper->formatter = $this->dumper->formatter();
        }
        ob_start();
        $this->dumper->dump($message, $var);
        return ob_get_clean();
    }

    public function testDump(): void
    {
        $this->assertEquals("\n# Test Var: [34m'stringValue'[0m\n", $this->dump('Test Var', 'stringValue') , 'String');
    }
}
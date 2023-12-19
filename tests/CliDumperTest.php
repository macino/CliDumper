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

        $testArr = [
            "string" => "Hello",
            "integer" => 123,
            "float" => 3.14,
            "boolean" => true,
            "null" => null,
            "numericArray" => [1, 2, 3],
            "emptyArray" => [],
            "object" => new stdClass()
        ];

        $this->assertEquals("\n# Test Var: \nstring: [34m'Hello'[0m\ninteger: [36m123[0m\nfloat: [36m3.14[0m\nboolean: [35mTRUE[0m\nnull:: [35mNULL[0m\nnumericArray:: 0: [36m1[0m, 1: [36m2[0m, 2: [36m3[0m\nemptyArray:: [32m... @empty[0m\nobject:: \n", $this->dump('Test Var', $testArr) , 'Complex');

    }
}
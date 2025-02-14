<?php

use Macino\CliDumper\Benchmark;
use PHPUnit\Framework\TestCase;
use Macino\CliDumper\CliDumper;

class CliDumperTest extends TestCase
{
    private CliDumper $dumper;
    private function dump(string $message, mixed $var, bool $separateLeafs = false, bool $separate = false): string
    {
        if (!isset($this->dumper)) {
            $this->dumper = new Macino\CliDumper\CliDumper();
            $this->dumper->formatter = $this->dumper->formatter();
        }
        ob_start();
        $this->dumper->dump($message, $var, $separateLeafs, $separate);
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

        $testArr = [
            "string" => "Hello",
            "integer" => 123,
            "float" => 3.14,
            "boolean" => true,
            "null" => null,
            "numericArray" => [1, 2, 3],
            "somethingArray" => [
                "something" => [
                    'a' => [1,2,3],
                    'b' => [4,5,6],
                    'c' => [7,8,9],
                ],
                "something2" => [
                    'a' => [1,2,3],
                    'b' => [4,5,6],
                    'c' => [7,8,9],
                ],
            ],
            "object" => new stdClass()
        ];

        $this->assertEquals("\n# Test Var: \nstring: [34m'Hello'[0m\ninteger: [36m123[0m\nfloat: [36m3.14[0m\nboolean: [35mTRUE[0m\nnull:: [35mNULL[0m\nnumericArray:: 0: [36m1[0m, 1: [36m2[0m, 2: [36m3[0m\nsomethingArray: \n.	something: \n.	.	a:: 0: [36m1[0m, 1: [36m2[0m, 2: [36m3[0m\n.	.	b:: 0: [36m4[0m, 1: [36m5[0m, 2: [36m6[0m\n.	.	c:: 0: [36m7[0m, 1: [36m8[0m, 2: [36m9[0m\n.	something2: \n.	.	a:: 0: [36m1[0m, 1: [36m2[0m, 2: [36m3[0m\n.	.	b:: 0: [36m4[0m, 1: [36m5[0m, 2: [36m6[0m\n.	.	c:: 0: [36m7[0m, 1: [36m8[0m, 2: [36m9[0m\nobject:: \n", $this->dump('Test Var', $testArr) , 'Complex');

        $testArr = [
            "string" => "Hello",
            "integer" => 123,
            "float" => 3.14,
            "boolean" => true,
            "null" => null,
            "numericArray" => [1, 2, 3],
            "somethingArray" => [
                "something" => [
                    'a' => [
                        ['subA' => ['a' => 1, 'b' => 2, 'c' => 3]],
                        ['subB' => ['a' => 4, 'b' => 5, 'c' => 6]],
                        ['subC' => ['a' => 7, 'b' => 8, 'c' => 9]],
                    ],
                    'b' => [
                        ['subA' => ['a' => 1, 'b' => 2, 'c' => 3]],
                        ['subB' => ['a' => 4, 'b' => 5, 'c' => 6]],
                        ['subC' => ['a' => 7, 'b' => 8, 'c' => 9]],
                    ],
                    'c' => [
                        ['subA' => ['a' => 1, 'b' => 2, 'c' => 3]],
                        ['subB' => ['a' => 4, 'b' => 5, 'c' => 6]],
                        ['subC' => ['a' => 7, 'b' => 8, 'c' => 9]],
                    ],
                ],
                "something2" => [
                    'a' => [1,2,3],
                    'b' => [4,5,6],
                    'c' => [7,8,9],
                ],
            ],
            "object" => new stdClass()
        ];

        $this->assertEquals(
            "\n# Test Var: \nstring: [34m'Hello'[0m\ninteger: [36m123[0m\nfloat: [36m3.14[0m\nboolean: [35mTRUE[0m\nnull:: [35mNULL[0m\nnumericArray:: \n.	0: [36m1[0m\n.	1: [36m2[0m\n.	2: [36m3[0m\nsomethingArray: \n.	something: \n.	.	a: \n.	.	.	0: \n.	.	.	.	subA:: \n.	.	.	.	.	a: [36m1[0m\n.	.	.	.	.	b: [36m2[0m\n.	.	.	.	.	c: [36m3[0m\n.	.	.	1: \n.	.	.	.	subB:: \n.	.	.	.	.	a: [36m4[0m\n.	.	.	.	.	b: [36m5[0m\n.	.	.	.	.	c: [36m6[0m\n.	.	.	2: \n.	.	.	.	subC:: \n.	.	.	.	.	a: [36m7[0m\n.	.	.	.	.	b: [36m8[0m\n.	.	.	.	.	c: [36m9[0m\n.	.	b: \n.	.	.	0: \n.	.	.	.	subA:: \n.	.	.	.	.	a: [36m1[0m\n.	.	.	.	.	b: [36m2[0m\n.	.	.	.	.	c: [36m3[0m\n.	.	.	1: \n.	.	.	.	subB:: \n.	.	.	.	.	a: [36m4[0m\n.	.	.	.	.	b: [36m5[0m\n.	.	.	.	.	c: [36m6[0m\n.	.	.	2: \n.	.	.	.	subC:: \n.	.	.	.	.	a: [36m7[0m\n.	.	.	.	.	b: [36m8[0m\n.	.	.	.	.	c: [36m9[0m\n.	.	c: \n.	.	.	0: \n.	.	.	.	subA:: \n.	.	.	.	.	a: [36m1[0m\n.	.	.	.	.	b: [36m2[0m\n.	.	.	.	.	c: [36m3[0m\n.	.	.	1: \n.	.	.	.	subB:: \n.	.	.	.	.	a: [36m4[0m\n.	.	.	.	.	b: [36m5[0m\n.	.	.	.	.	c: [36m6[0m\n.	.	.	2: \n.	.	.	.	subC:: \n.	.	.	.	.	a: [36m7[0m\n.	.	.	.	.	b: [36m8[0m\n.	.	.	.	.	c: [36m9[0m\n.	something2: \n.	.	a:: \n.	.	.	0: [36m1[0m\n.	.	.	1: [36m2[0m\n.	.	.	2: [36m3[0m\n.	.	b:: \n.	.	.	0: [36m4[0m\n.	.	.	1: [36m5[0m\n.	.	.	2: [36m6[0m\n.	.	c:: \n.	.	.	0: [36m7[0m\n.	.	.	1: [36m8[0m\n.	.	.	2: [36m9[0m\nobject:: \n.	\n",
            $this->dump('Test Var', $testArr, true),
            'Complex'
        );

    }
    
    public function testDebugMessage()
    {
        if (!isset($this->dumper)) {
            $this->dumper = new Macino\CliDumper\CliDumper();
            $this->dumper->formatter = $this->dumper->formatter();
        }
        $message = 'This is a debug message';
        $expectedOutput = "@ [37mThis is a debug message[0m\n";

        ob_start();
        $this->dumper->dm($message);
        $result = ob_get_clean();

        $this->assertEquals($expectedOutput, $result, 'Debug message output is not correct');
    }

    public function testDumpBenchmark()
    {
        if (!isset($this->dumper)) {
            $this->dumper = new Macino\CliDumper\CliDumper();
            $this->dumper->formatter = $this->dumper->formatter();
        }
        $bm = new Benchmark('test bm');

        $expectedPattern = "/@ test bm: \e\[36m\d+\.\d{4,}ms\e\[0m\\n/";

        // Assuming `dumpBenchmark` calculates and dumps benchmark time
        ob_start();
        $this->dumper->dbm($bm);
        $result = ob_get_clean();

        $this->assertMatchesRegularExpression($expectedPattern, $result, 'Dump benchmark output is not correct');
    }
}
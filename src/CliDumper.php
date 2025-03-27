<?php

namespace Macino\CliDumper;

class CliDumper
{
    const FM_ERROR = CliFormat::FG_RED;
    const FM_NULL_BOOL = CliFormat::FG_PURPLE;
    const FM_STRING = CliFormat::FG_BLUE;
    const FM_TRUNC_EMPTY = CliFormat::FG_GREEN;
    const FM_NUM = CliFormat::FG_CYAN;

    /**
     * @var bool dump output is enabled
     */
    public bool $enabled = true;
    /**
     * @var int limit length of a string, 0 for no limit
     */
    public int $truncate = 80;
    
    /**
     * @var callable callback with params value and type, returning formatted string of a scalar value.
     */
    public $formatter;
    /**
     * @param string $msg message before variable dumping or just string export
     * @param mixed $data data for dump
     * @param bool $separateLeafs separate last scalar members and order them by key
     * @param bool $separate if true, a line of 80 dashes will be generated before any other output
     * @return void
     */
    public function dump(string $msg, mixed $data = null, bool $separateLeafs = false, bool $separate = false): void
    {
        if (!$this->enabled) return;
        if ($separate) echo "\n" . str_repeat('-', 80);
        if ($msg) {
            echo "\n" . '# ' . $msg . ': ';
            if (!(is_scalar($data) || is_null($data))) echo "\n";
        }
        $this->inlineDump($data, $separateLeafs);
    }

    /**
     * Alias for d
     *
     * @param string $msg message before variable dumping or just string export
     * @param mixed $data data for dump
     * @param bool $separateLeafs separate last scalar members and order them by key
     * @param bool $separate if true, a line of 80 '-' will be generated before any other output
     * @return void
     */
    public function d(string $msg, mixed $data = null, bool $separateLeafs = false, bool $separate = false): void{
        $this->dump($msg, $data, $separateLeafs, $separate);
    }

    /**
     * @param mixed $var - variable to be dumped
     * @param bool $separateLeafs - separate last members of object or array tree with a new line
     * @param bool $return - return string instead of printing it out
     * @return string - string of dumped $var if $return is true or empty string otherwise
     */
    public function inlineDump(mixed $var, bool $separateLeafs = false, bool $return = false): string
    {
        if (!$this->enabled) {
            return '';
        }
        if (!$this->formatter) {
            $this->formatter = fn(mixed $value, string $type) => $value;
        }
        $f = $this->formatter;
        $dump = function ($var, int $level = 0) use(&$dump, $separateLeafs, $f): string
        {
            # fce to determine if the given variable is leaf (scalar or null)
            $isLeaf = fn($v) => is_scalar($v) || is_null($v) || is_resource($v) || is_callable($v);
            # fce to determine if the given variable is a parent with only leafs as children or is empty
            $hasOnlyLeafs = fn($v) => !is_scalar($v)
                && empty(array_filter(
                    (array)$v,
                    fn($v_) => !(is_scalar($v_) || is_null($v_))
                ))
            ;
            # cast specific types to more readable form and return them immediately
            if ($isLeaf($var)) {
                if (is_null($var)) return $f("NULL", 'null');
                elseif (is_resource($var)) return $f("@resource", 'resource');
                elseif (is_callable($var)) return $f("@closure", 'closure');
                elseif (is_bool($var)) return $f($var ? 'TRUE' : 'FALSE', 'bool');
                elseif (is_string($var)) return $f(
                    $this->truncate && strlen($var) > $this->truncate
                        ? "'" . substr($var, 0, $this->truncate) . $f('... @truncated', 'trunc') . '\''
                        : "'$var'",
                    'string'
                );
                elseif (is_numeric($var)) return $f($var, 'num');
                else return $f($var, 'other');
            }
            # print leafs only, the recursion is just for one more level to get values of leafs
            elseif ($hasOnlyLeafs($var)) {
                if (empty($var)) return $f('... @empty', 'empty');
                $rtn = [];
                foreach ((array)$var as $k => $v) {
                    $rtn[$k] = $f($k, 'key') . ": " . $dump($v);
                }
                # sort by key so the searching through the logs would be more humane
                ksort($rtn);
                if ($separateLeafs) {
                    $separator = "\n" . str_repeat(".\t", $level);
                    return $separator . implode($separator, $rtn);
                }
                return implode(', ', $rtn);
            }
            # print multi-level object / array with increasing the indentation for each level
            elseif (is_iterable((array)$var)) {
                $rtn = [];
                foreach ((array)$var as $k => $v) {
                    $rtn[] = str_repeat(".\t", $level)
                        . $f($k, 'key') . ($hasOnlyLeafs($v) ? ":: " : ": ")
                        . ($isLeaf($v) || $hasOnlyLeafs($v) ? '' : "\n")
                        . $dump($v, $level + 1)
                    ;
                }
                return implode("\n", $rtn);

            }
            # something went wrong
            return $f("#dump error#", 'error');
        };
        if ($return) return $dump($var);
        echo $dump($var) . "\n";
        return '';
    }

    /**
     * Default output formatter
     * @return callable
     */

    public function formatter(): callable
    {
        return function (mixed $v, string $type)
        {
            return match ($type) {
                'error' => CliFormat::format($v, CliDumper::FM_ERROR),
                'null', 'bool', 'resource', 'closure' => CliFormat::format($v, CliDumper::FM_NULL_BOOL),
                'string' => CliFormat::format($v, CliDumper::FM_STRING),
                'trunc', 'empty' => CliFormat::format($v, CliDumper::FM_TRUNC_EMPTY),
                'num' => CliFormat::format($v, CliDumper::FM_NUM),
                default => $v,
            };
        };
    }

    /**
     * Prints a debug message in the CLI with a specific type and formatting.
     *
     * The type of the debug message determines its color when displayed in the CLI:
     * - 'info' (default): Light gray
     * - 'warn': Yellow
     * - 'error': Red
     *
     * @param string $msg The debug message to display.
     * @param string $type The type of the message ('info', 'warn', or 'error').
     * @return void
     */
    public function debugMessage(string $msg, string $type = 'info'): void
    {
        $formats = [
            'info' => CliFormat::FG_LIGHT_GRAY,
            'warn' => CliFormat::FG_YELLOW,
            'error' => CliFormat::FG_RED,
        ];
        $color = $formats[$type] ?? CliFormat::FG_LIGHT_GRAY;
        echo "@ " . CliFormat::format($msg, $color) . "\n";
    }

    
    /**
     * Alias for debugMessage
     *
     * Outputs a debug message to the CLI with the provided formatting type.
     *
     * @param string $msg The debug message to display.
     * @param string $type The type of the message ('info', 'warn', or 'error').
     *                      Defaults to 'info'.
     * @return void
     */
    public function dm(string $msg, string $type = 'info'): void
    {
        $this->debugMessage($msg, $type);
    }

    
    /**
     * Outputs benchmarking information to the CLI.
     *
     * Formats the benchmark name alongside the time measured in milliseconds,
     * with an optional message appended at the end.
     *
     * Example output:
     * @ BenchmarkName: 123ms Optional message
     *
     * @param Benchmark $bm The benchmark instance which provides name and time.
     * @param string $msg Optional message to be appended to the output.
     * @return void
     */
    public function dumpBenchMark(Benchmark $bm, string $msg = ''): void
    {
        echo sprintf(
            "@ %s: [%s Δ %s]%s\n",
            $bm->getName(),
            CliFormat::format($bm->getMarkMs() . 'ms', CliDumper::FM_NUM),
            CliFormat::format($bm->getMarkDeltaMs() . 'ms', CliDumper::FM_NUM),
            $msg ? ' ' . $msg : ''
        );
    }


    /**
     * Alias for dumpBenchMark
     *
     * Outputs a benchmark instance alongside its time in milliseconds and an optional message.
     *
     * This method serves as a shorthand for the dumpBenchMark method, with identical behavior.
     *
     * Example output:
     * @ BenchmarkName: 123ms Optional message
     *
     * @param Benchmark $bm The benchmark instance which provides name and time.
     * @param string $msg Optional message to be appended to the output.
     * @return void
     */
    public function dbm(Benchmark $bm, string $msg = ''): void
    {
        $this->dumpBenchMark($bm, $msg);
    }

    /**
     * Use to export a global function cd() => CliDumper::cd()
     *
     * @return CliDumper
     */
    public static function cd(): CliDumper
    {
        $cli = new CliDumper();
        $cli->formatter = $cli->formatter();
        return $cli;
    }
}
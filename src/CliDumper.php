<?php

namespace Macino\CliDumper;

class CliDumper
{
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
     * Example of a formatter:
     * <code>
     *     $this->dumper->formatter = function(mixed $v, string $type) {
     *         switch ($type) {
     *             case 'error':
     *                 return CLI::color($v, 'red');
     *             case 'null':
     *             case 'bool':
     *                 return CLI::color($v, 'purple');
     *             case 'string': return CLI::color($v, 'blue');
     *             case 'trunc':
     *             case 'empty':
     *                 return CLI::color($v, 'yellow');
     *             case 'num': return CLI::color($v, 'green');
     *         }
     *         return $v;
     *     };
     * </code>
     */
    public $formatter;
    /**
     * @param string $msg message before variable dumping or just string export
     * @param mixed $data data for dump
     * @param bool $separate if true, a line of 80 dashes will be generated before any other output
     * @param bool $separateLeafs separate last scalar members and order them by key
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
     * @param bool $separate if true, a line of 80 '-' will be generated before any other output
     * @param bool $separateLeafs separate last scalar members and order them by key
     * @return void
     */
    public function d(string $msg, mixed $data = null, bool $separateLeafs = false, bool $separate = false): void{
        $this->dump($msg, $data, $separate, $separateLeafs);
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
            $isLeaf = fn($v) => is_scalar($v) || is_null($v);
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
                    return implode("\n", $rtn);
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
            switch ($type) {
                case 'error':
                    return CLI::color($v, 'red');
                case 'null':
                case 'bool':
                    return CLI::color($v, 'purple');
                case 'string':
                    return CLI::color($v, 'blue');
                case 'trunc':
                case 'empty':
                    return CLI::color($v, 'yellow');
                case 'num':
                    return CLI::color($v, 'green');
            }
            return $v;
        };
    }
}
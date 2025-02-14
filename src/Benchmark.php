<?php

namespace Macino\CliDumper;

class Benchmark
{
    private int $startTs;
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->start();
    }

    public function start()
    {
        $this->startTs = microtime(true);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getStartTs()
    {
        return $this->startTs;
    }

    public function getMarkTs()
    {
        return microtime(true) - $this->startTs;
    }
    
    private function ts2ms(float $timestamp): string
    {
        return number_format($timestamp * 1000, 4);
    }


    public function getStartMs(): string
    {
        return $this->ts2ms($this->startTs);
    }


    public function getMarkMs(): string
    {
        return $this->ts2ms($this->getMarkTs());
    }
}
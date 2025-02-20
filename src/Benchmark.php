<?php

namespace Macino\CliDumper;

class Benchmark
{
    private float $startTs;
    private float $markTs;
    private float $prevMarkTs;
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->start();
    }

    public function start(): void
    {
        $this->startTs = $this->markTs = $this->prevMarkTs = microtime(true);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getStartTs(): int
    {
        return $this->startTs;
    }

    public function getMarkTs()
    {
        $this->prevMarkTs = $this->markTs;
        $this->markTs = microtime(true);
        return $this->markTs - $this->startTs;
    }

    public function getMarkDelta(): float
    {
        return $this->markTs - $this->prevMarkTs;
    }
    
    private function ts2ms(float $timestamp): string
    {
        return number_format($timestamp * 1000, 4, '.', ' ');
    }


    public function getStartMs(): string
    {
        return $this->ts2ms($this->startTs);
    }


    public function getMarkMs(): string
    {
        return $this->ts2ms($this->getMarkTs());
    }

    public function getMarkDeltaMs(): string
    {
        return $this->ts2ms($this->getMarkDelta());
    }
}
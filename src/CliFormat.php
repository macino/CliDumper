<?php

namespace Macino\CliDumper;

class CliFormat
{
    const FG_RED = '\e[31m%s';
    const FG_GREEN = '\e[32m%s';
    const FG_YELLOW = '\e[33m%s';
    const FG_BLUE = '\e[34m%s';
    const FG_PURPLE = '\e[35m%s';
    const FG_CYAN = '\e[36m%s';
    const FG_WHITE = '\e[37m%s';
    const BG_RED = '\e[41m%s';
    const BG_GREEN = '\e[42m%s';
    const BG_YELLOW = '\e[43m%s';
    const BG_BLUE = '\e[44m%s';
    const BG_PURPLE = '\e[45m%s';
    const BG_CYAN = '\e[46m%s';
    const BG_WHITE = '\e[47m%s';
    const RESET = '\e[0m';

    public function format(string $message, string|array $format): string
    {
        if (is_string($format)) {
            $format = [$format];
        }
        $currentFormat = array_unshift($format);
        return $this->format_($message, $currentFormat, $format);
    }

    private function format_(string $message, string $currentFormat, array $remainingFormats): string
    {
        $message = sprintf($currentFormat, $message);
        return $remainingFormats
            ? $this->format_(
                sprintf($currentFormat, $message),
                $remainingFormats[0],
                array_slice($remainingFormats, 1),
            )
            : sprintf($currentFormat . self::RESET, $message)
        ;
    }
}
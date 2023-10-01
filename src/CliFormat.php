<?php

namespace Macino\CliDumper;

class CliFormat
{
    /* other possible formats https://misc.flogisoft.com/bash/tip_colors_and_formatting */
    const FG_RED = "\e[31m";
    const FG_GREEN = "\e[32m";
    const FG_YELLOW = "\e[33m";
    const FG_BLUE = "\e[34m";
    const FG_PURPLE = "\e[35m";
    const FG_CYAN = "\e[36m";
    const FG_WHITE = "\e[37m";
    const BG_RED = "\e[41m";
    const BG_GREEN = "\e[42m";
    const BG_YELLOW = "\e[43m";
    const BG_BLUE = "\e[44m";
    const BG_PURPLE = "\e[45m";
    const BG_CYAN = "\e[46m";
    const BG_WHITE = "\e[47m";
    const RESET = "\e[0m";

    public static function format(string $message, string|array $format): string
    {
        if (is_string($format)) {
            $format = [$format];
        }
        return implode('', $format) . $message . CliFormat::RESET;
    }
}
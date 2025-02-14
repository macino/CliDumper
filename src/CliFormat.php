<?php

namespace Macino\CliDumper;

class CliFormat
{
    /* other possible formats https://misc.flogisoft.com/bash/tip_colors_and_formatting */
    const FG_BLACK = "\e[30m";
    const FG_RED = "\e[31m";
    const FG_GREEN = "\e[32m";
    const FG_YELLOW = "\e[33m";
    const FG_BLUE = "\e[34m";
    const FG_PURPLE = "\e[35m";
    const FG_CYAN = "\e[36m";
    const FG_LIGHT_GRAY = "\e[37m";
    const FG_DEFAULT = "\e[39m";
    const FG_DARK_GRAY = "\e[90m";
    const FG_LIGHT_RED = "\e[91m";
    const FG_LIGHT_GREEN = "\e[92m";
    const FG_LIGHT_YELLOW = "\e[93m";
    const FG_LIGHT_BLUE = "\e[94m";
    const FG_LIGHT_PURPLE = "\e[95m";
    const FG_LIGHT_CYAN = "\e[96m";
    const FG_WHITE = "\e[97m";

    const BG_BLACK = "\e[40m";
    const BG_RED = "\e[41m";
    const BG_GREEN = "\e[42m";
    const BG_YELLOW = "\e[43m";
    const BG_BLUE = "\e[44m";
    const BG_PURPLE = "\e[45m";
    const BG_CYAN = "\e[46m";
    const BG_WHITE = "\e[47m";
    
    const BG_DEFAULT = "\e[49m";
    const BG_DARK_GRAY = "\e[100m";
    const BG_LIGHT_RED = "\e[101m";
    const BG_LIGHT_GREEN = "\e[102m";
    const BG_LIGHT_YELLOW = "\e[103m";
    const BG_LIGHT_BLUE = "\e[104m";
    const BG_LIGHT_PURPLE = "\e[105m";
    const BG_LIGHT_CYAN = "\e[106m";
    const BG_LIGHT_GRAY = "\e[107m";
    const RESET = "\e[0m";

    public static function format(string $message, string|array $format): string
    {
        if (is_string($format)) {
            $format = [$format];
        }
        return implode('', $format) . $message . CliFormat::RESET;
    }
}
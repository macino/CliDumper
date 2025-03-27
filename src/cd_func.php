<?php

namespace Macino\CliDumper;

function cd(): CliDumper
{
    $cli = new CliDumper();
    $cli->formatter = $cli->formatter();
    return $cli;
}
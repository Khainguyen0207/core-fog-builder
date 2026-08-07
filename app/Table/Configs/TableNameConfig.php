<?php

namespace App\Table\Configs;

interface TableNameConfig
{
    public function setNameTable(string $name): static;

    public function getNameTable(): string;
}

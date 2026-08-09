<?php

namespace Modules\Shared\Tables\Contracts;

interface TableName
{
    public function setNameTable(string $name): static;

    public function getNameTable(): string;
}

<?php

namespace Modules\Shared\Tables\Operations;

class LockedOperation extends BaseOperation
{
    public function __construct()
    {
        $this->setDescription('Do you want to lock or unlock this member?')
            ->setName('btn-lock')
            ->setMethod('POST')
            ->setAttributes(['class' => 'btn btn-icon btn-sm btn-danger text-white mb-1'])
            ->setIcon('bx bxs-lock-open bx-flip-horizontal bx-tada');
    }
}

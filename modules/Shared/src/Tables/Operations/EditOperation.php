<?php

namespace Modules\Shared\Tables\Operations;

class EditOperation extends BaseOperation
{
    public function __construct()
    {
        $this->setName('btn-edit')
            ->setMethod('PUT')
            ->setAttributes(['class' => 'btn btn-icon btn-sm btn-info text-white mb-1'])
            ->setIcon('bx bx-edit');
    }
}

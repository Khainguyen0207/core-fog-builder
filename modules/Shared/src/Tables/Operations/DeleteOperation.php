<?php

namespace Modules\Shared\Tables\Operations;

class DeleteOperation extends BaseOperation
{
    public function __construct()
    {
        $this->setIcon('bx bx-trash')
            ->setName('delete')
            ->setAttributes(['class' => 'btn btn-icon btn-sm btn-danger text-white mb-1']);
    }
}

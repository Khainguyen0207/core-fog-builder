<?php

namespace Modules\Shared\Tables\Operations;

class PreviewOperation extends BaseOperation
{
    public function __construct()
    {
        $this->setName('btn-preview')
            ->setMethod('GET')
            ->setAttributes([
                'class' => 'btn btn-icon btn-sm btn-success text-white mb-1',
                'target' => '_blank',
            ])
            ->hasModal(false)
            ->setIcon('bx bx-show');
    }
}

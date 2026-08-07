<?php

namespace App\Table\HeaderActions;

class CreateHeaderAction extends BaseHeaderAction
{
    public function __construct()
    {
        $this
            ->setName('btn-create')
            ->setAttributes([
                'class' => 'btn btn-outline-info col-md-auto me-3 mb-3',
            ])
            ->setLabel('Create')
            ->setIcon('bx bx-plus');
    }
}

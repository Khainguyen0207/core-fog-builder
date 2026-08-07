<?php

namespace App\Table\Operations;

class LockedOperation extends BaseOperation
{
    public function __construct()
    {
        $this
            ->setDescription('Bạn có muốn khóa/mở khóa thành viên')
            ->setName('btn-lock')
            ->setMethod('POST')
            ->setAttributes([
                'class' => 'btn btn-icon btn-sm btn-danger text-white mb-1',
            ])
            ->setIcon('bx bxs-lock-open bx-flip-horizontal bx-tada');
    }
}

<?php

namespace Modules\Shared\BulkActions\Contracts;

use Illuminate\Http\Request;

interface BulkDeleteHandler
{
    /** @param array<int, int> $ids */
    public function authorize(Request $request, array $ids): bool;

    /** @param array<int, int> $ids */
    public function delete(array $ids): int;
}

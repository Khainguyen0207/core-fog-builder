<?php

namespace Modules\Shared\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Shared\BulkActions\BulkDeleteRegistry;
use Throwable;

class BulkDeleteController extends Controller
{
    public function __construct(
        private readonly BulkDeleteRegistry $registry,
        private readonly ValidationFactory $validation,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validator = $this->validation->make($request->all(), [
            'resource' => ['required', 'string', 'max:255'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'data' => ['errors' => $validator->errors()->toArray()],
                'message' => 'The bulk delete request is invalid.',
            ], 422);
        }

        $validated = $validator->validated();
        $ids = array_map(static fn (mixed $id): int => (int) $id, $validated['ids']);

        try {
            $handler = $this->registry->resolve($validated['resource']);

            if ($handler === null) {
                return response()->json([
                    'error' => true,
                    'data' => null,
                    'message' => 'The requested resource does not support bulk deletion.',
                ], 404);
            }

            if (! $handler->authorize($request, $ids)) {
                return response()->json([
                    'error' => true,
                    'data' => null,
                    'message' => 'You are not authorized to bulk delete this resource.',
                ], 403);
            }

            $deletedCount = DB::transaction(fn (): int => $handler->delete($ids));

            return response()->json([
                'error' => false,
                'data' => [
                    'deleted_count' => $deletedCount,
                    'ids' => $ids,
                ],
                'message' => 'Selected records were deleted successfully.',
            ]);
        } catch (AuthorizationException) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'You are not authorized to bulk delete this resource.',
            ], 403);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'The selected records could not be deleted.',
            ], 500);
        }
    }
}

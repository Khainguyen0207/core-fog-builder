@extends('admin.layouts.contentLayout')

@section('title', $table->getNameTable() ?? 'Dashboard - Analytics')

@section('content')
    @include('admin.components.tables.table')
@endsection

@push('modals')
    <div class="modal fade" id="confirm-modal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="model-content">
                        <div class="row">
                            <h3 class="text-warning">Notification</h3>
                        </div>
                        <div class="row g-6">
                            <h4><span class="id-row-del-content">Bạn có muốn xoá thành viên</span> <span
                                    class="text-info id-row-del"></span> ?</h4>
                        </div>
                    </div>
                </div>

                <div class="modal-footer gap-4 d-flex">
                    <form action="/" id="form-confirm-modal">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-primary">OK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bulk-confirm-modal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="model-content">
                        <div class="row">
                            <h3 class="text-warning">Nhắc nhở</h3>
                        </div>
                        <div class="row g-6">
                            <h4>Bạn có muốn xoá <span class="text-danger bulk-delete-count">0</span> bản ghi đã chọn?</h4>
                        </div>
                    </div>
                </div>

                <div class="modal-footer gap-4 d-flex">
                    <form action="{{ route('admin.bulk-delete') }}" id="form-bulk-confirm-modal" method="POST">
                        @csrf
                        <input type="hidden" id="bulk-ids-input" name="ids" value="">
                        <input type="hidden" id="bulk-resource-input" name="resource" value="">

                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-danger">Xóa tất cả</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

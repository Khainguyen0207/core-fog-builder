<div class="modal fade" id="create-modal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <h3 class="modal-header-content">Thêm Người Dùng</h3>
            </div>
            <form class="form-sample col-12 " method="POST"
                  action="{{ route('admin.users.store') }}" enctype="multipart/form-data"
                  data-bs-target="form-App\Forms\CustomerForm" id="App\Forms\CustomerForm-generate-form" novalidate="">
                @csrf
                @method('POST')

                <div class="row p-4">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name</label>

                        <input name="customer_name" type="text" class="form-control"
                               placeholder="Enter your username..."
                               aria-describedby="helperText" required="" autocomplete="on">

                        <div class="invalid-feedback">Name is required</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>

                        <input name="email" type="text" class="form-control" placeholder="Enter your email..."
                               aria-describedby="helperText" required="" autocomplete="on">

                        <div class="invalid-feedback">Email is required</div>
                    </div>

                    <div class="col-md-6 mb-3 form-password-toggle">
                        <label for="tel_num" class="form-label">Number Phone</label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="tel_num"
                                   name="tel_num" placeholder="Enter Number Phone"
                                   aria-describedby="basic-default-password" autocomplete="off">
                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                        </div>

                        <div class="invalid-feedback">Tel is required</div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="is_active" class="form-label">Trạng thái</label>
                            <select class="selectpicker w-100" data-style="btn-default"
                                    tabindex="null" name="is_active">
                                <option value="1">Đang hoạt động</option>
                                <option value="0">Tạm khóa</option>
                            </select>
                            <div class="invalid-feedback">Status is required</div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Address</label>

                        <input name="address" type="text" class="form-control" placeholder="Enter your username..."
                               aria-describedby="helperText" required="" autocomplete="on">

                        <div class="invalid-feedback">Address is required</div>
                    </div>
                </div>
                <div class="modal-footer gap-4 d-flex">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>

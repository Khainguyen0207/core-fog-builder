@php
    $config = [
        'name' => 'customer',
        'action' => isset($customer) ? route('admin.customers.edit', $customer) : route('admin.customers.store'),
        'method' => isset($customer) ? 'PUT' : 'POST',
    ];
@endphp

<form class="form-sample col-12" method="POST" action="{{ $config['action'] }}" enctype="multipart/form-data"
    data-bs-target="form-{{ $config['name'] }}" id="{{ $config['name'] }}-generate-form">
    @method($config['method'])
    @csrf

    <div class="row flex-row-reverse">
        <div class="col-md-3 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-4">Action</h3>
                    <button type="submit" class="btn btn-primary btn-icon-text mb-3 w-100">
                        <i class="mdi mdi-file-check btn-icon-prepend"></i> Submit
                    </button>
                    <button type="button" class="btn btn-danger btn-icon-text mb-3 w-100">
                        <i class="mdi mdi-file-cancel btn-icon-prepend"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-section mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Nhập họ tên">
                                    <span class="invalid-feedback">Vui lòng nhập họ tên hợp lệ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Nhập email">
                                    <span class="invalid-feedback">Vui lòng nhập email hợp lệ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Nhập mật khẩu mới">
                                    <span class="menu-icon position-absolute" data-bs-toggle="togglePassword"
                                        style="top: 40%; right: 20px;">
                                        <i class="mdi mdi-eye"></i>
                                    </span>
                                    <span class="invalid-feedback">Mật khẩu phải có ít nhất 6 ký tự</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Nhập lại mật khẩu">
                                    <span class="menu-icon position-absolute" data-bs-toggle="togglePassword"
                                        style="top: 40%; right: 20px;">
                                        <i class="mdi mdi-eye"></i>
                                    </span>
                                    <span class="invalid-feedback">Mật khẩu xác nhận không khớp</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Nhập số điện thoại">
                                    <span class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="cash" class="form-label">Số dư tài khoản</label>
                                    <input type="number" step="0.01" class="form-control" id="cash"
                                        name="cash" placeholder="Nhập số dư" value="0">
                                    <span class="invalid-feedback">Vui lòng nhập số dư hợp lệ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select id="status" name="status" class="form-control form-select">
                                        <option value="active">Active</option>
                                        <option value="locked">Locked</option>
                                    </select>
                                    <span class="invalid-feedback">Vui lòng chọn trạng thái</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="birthday" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="birthday" name="birthday">
                                    <span class="invalid-feedback">Vui lòng chọn ngày sinh hợp lệ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">Giới tính</label>

                                    <select id="gender" name="gender" class="form-control form-select">
                                        <option value="male" selected>Nam</option>
                                        <option value="female">Nữ</option>
                                        <option value="other">Khác</option>
                                    </select>
                                    <span class="invalid-feedback">Vui lòng chọn giới tính</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Nhập địa chỉ">
                                    <span class="invalid-feedback">Vui lòng nhập địa chỉ</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <h3 class="mb-4">Avatar</h3>
                                <div class="file-upload-wrapper">
                                    <div class="file-upload-preview mb-3 text-center">
                                    </div>
                                    <div class="file-upload-input">
                                        <input type="file" class="form-control" id="avatar" name="avatar"
                                            accept="image/*">
                                        <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF. Max size:
                                            2MB</small>
                                        <span class="invalid-feedback">Vui lòng chọn file ảnh hợp lệ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('footer')
    <script>
        $(function() {
            const $formName = @json($config['name']);
            const $method = @json($config['method']);
            const $dataForm = $('.form-has-data');
            const $form = $(`form[data-bs-target="form-${$formName}"]`);

            const $id = $dataForm.attr('id') + '-form';
            const $dataUrl = $dataForm.attr('data-url');

            $form.attr('action', $dataUrl);
            $form.attr('id', $id);

            actionForm($form)
        });

        @if (isset($customer))
            $(function() {
                const $myVar = @json($customer);

                const $dataForm = $('.form-has-data');
                const $id = $dataForm.attr('id') + '-form';

                generateForm($myVar, $id)
            });
        @endif
    </script>
@endpush

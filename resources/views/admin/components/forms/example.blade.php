<div class="col-md-6">
    <label for="defaultFormControlInput" class="form-label">Name</label>
    <input type="text" class="form-control" id="defaultFormControlInput"
           placeholder="John Doe" aria-describedby="defaultFormControlHelp">
    <div id="defaultFormControlHelp" class="form-text">We'll never share your details with
        anyone else.
    </div>
</div>
<div>
    <div class="form-group mb-3">
        <label for="name" class="form-label">Username</label>
        <input type="text" class="form-control" id="name" name="name"
               placeholder="Enter username" required>
        <div class="invalid-feedback">Username is required</div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email"
               placeholder="Enter email" required>
    </div>
    <div class="invalid-feedback">Email is required</div>
</div>
<div class="col-md-6">
    <div class="form-password-toggle mb-3">
        <label class="form-label" for="password">Password</label>
        <div class="input-group input-group-merge">
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="············" aria-describedby="basic-default-password"
                   autocomplete="off">
            <span class="input-group-text cursor-pointer" id="show-password"><i
                    class="icon-base bx bx-hide"></i></span>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-password-toggle mb-3">
        <label class="form-label" for="password">Confirm password</label>
        <div class="input-group input-group-merge">
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" placeholder="············"
                   aria-describedby="basic-default-password" autocomplete="off">
            <span class="input-group-text cursor-pointer" id="show-password-confirmation"><i
                    class="icon-base bx bx-hide"></i></span>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label for="phone" class="form-label">Number phone</label>
        <input type="text" class="form-control" id="phone" name="phone"
               placeholder="Enter number phone">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label for="cash" class="form-label">Balance</label>
        <input type="number" step="0.01" class="form-control" id="cash" name="cash"
               placeholder="Enter balance" value="0" disabled>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="selectpicker w-100" data-style="btn-default"
                tabindex="null" name="status">
            <option value="active">Active</option>
            <option value="inactive">Locked</option>
        </select>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label for="birthday" class="form-label">Birthday</label>
        <input type="date" class="form-control" id="birthday" name="birthday">
    </div>
</div>
<div class="col-md-6">
    <label for="selectpickerBasic" class="form-label">Sex</label>
    <div class="dropdown bootstrap-select w-100">
        <select class="selectpicker w-100" data-style="btn-default"
                tabindex="null" name="gender">
            <option value="male" selected>Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address"
               placeholder="Enter address">
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-12">
        <h3 class="mb-4">Avatar</h3>
        <div class="file-upload-wrapper">
            <div class="file-upload-input">
                <input type="file"
                       class="form-control"
                       id="avatar"
                       name="avatar"
                       accept="image/*">
                <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF. Max size:
                    2MB</small>
                <span class="invalid-feedback">Vui lòng chọn file ảnh hợp lệ</span>
            </div>
        </div>
    </div>
</div>


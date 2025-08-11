<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Custom Feature</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Tính năng tùy chỉnh</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="mdi mdi-information-outline"></i>
                    Đây là một menu item tùy chỉnh được thêm vào sidebar thông qua <code>add_sidebar_item()</code>
                </div>
                
                <p>Menu item này chỉ hiển thị cho những user có quyền <code>admin.dashboard</code>.</p>
                
                <div class="mt-3">
                    <a href="<?= admin_url('sidebar-demo') ?>" class="btn btn-primary">
                        <i class="mdi mdi-arrow-left"></i> Quay lại Demo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info mb-48">
                        <h4 class="card-title"><?= lang('Auth.register') ?></h4>
                        <p class="card-category">Buat akun petugas</p>
                    </div>
                    <div class="card-body mx-5 my-3">

                    <?php if (session()->has('msg')) : ?>
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <?= session('msg') ?>
                        </div>
                    <?php endif ?>

                        <form action="<?= site_url('admin/petugas/simpan') ?>" method="post" class="form-default">
                            <?= csrf_field() ?>

                            <div class="form-group mt-0">
                                <label for="email"><?= lang('Auth.email') ?></label>
                                <input type="email" id="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="example@email.com" value="<?= old('email') ?>" required>
                                <?php if (session('errors.email')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.email') ?>
                                    </div>
                                <?php endif ?>
                            </div>

                            <div class="form-group mt-4">
                                <label for="username"><?= lang('Auth.username') ?></label>
                                <input type="text" id="username" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="yourusername" value="<?= old('username') ?>" required>
                                <div class="invalid-feedback">
                                    <?= session('errors.username') ?>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <label for="password"><?= lang('Auth.password') ?></label>
                                <input type="password" id="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" autocomplete="off" required minlength="6">
                                <div class="invalid-feedback">
                                    <?= session('errors.password') ?>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <label for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                                <input type="password" id="pass_confirm" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" autocomplete="off" minlength="6">
                                <div class="invalid-feedback">
                                    <?= session('errors.pass_confirm') ?>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <label for="role">Role</label><br>
                                <input type="radio" name="role" value="admin" <?= old('role') == "admin" ? 'checked="checked"' : ''; ?> required> Administrator&nbsp;
                                <input type="radio" name="role" value="opscan" class="ml-3" <?= old('role') == "opsanc" ? 'checked="checked"' : ''; ?> required> Operator Scan&nbsp;
                                <input type="radio" name="role" value="petugas" class="ml-3"  <?= old('petugas') ? 'checked="checked"' : ''; ?> required> Petugas&nbsp;
                            </div>

                            <button type="submit" class="btn btn-primary mt-4"><?= lang('Auth.register') ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
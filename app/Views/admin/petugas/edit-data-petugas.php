<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title"><b>Form Edit Petugas</b></h4>

               </div>
               <div class="card-body mx-5 my-3">

                  <form action="<?= base_url('admin/petugas/edit'); ?>" method="post" class="form-default">
                     <?= csrf_field() ?>
                     <?php $validation = \Config\Services::validation(); ?>

                     <?php if (session()->getFlashdata('msg')) : ?>
                        <div class="pb-2">
                           <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                 <i class="material-icons">close</i>
                              </button>
                              <?= session()->getFlashdata('msg') ?>
                           </div>
                        </div>
                     <?php endif; ?>

                     <input type="hidden" name="id" value="<?= $data['id']; ?>">

                     <div class="form-group mt-0">
                        <label for="username">Username</label>
                        <input type="text" id="username" class="form-control <?= $validation->getError('username') ? 'is-invalid' : ''; ?>" name="username" placeholder="username123" value="<?= old('username') ?? $oldInput['username'] ?? $data['username'] ?>">
                        <div class="invalid-feedback">
                           <?= $validation->getError('username'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control <?= $validation->getError('email') ? 'is-invalid' : ''; ?>" name="email" placeholder="email@example.com" value="<?= old('email') ?? $oldInput['email'] ?? $data['email'] ?>">
                        <div class="invalid-feedback">
                           <?= $validation->getError('email'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="password">Password baru</label>
                        <input type="password" id="password" class="form-control <?= $validation->getError('password') ? 'is-invalid' : ''; ?>" name="password">
                        <div class="invalid-feedback">
                           <?= $validation->getError('password'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="role" class="">Role</label><br>
                        <input type="radio" name="role" value="admin" class="ml-3" <?= $data['is_superadmin'] == 1 ? 'checked="checked"' : ''; ?>> Administrator&nbsp;
                        <input type="radio" name="role" value="opscan" class="ml-3" <?= $data['is_operator'] == 1 ? 'checked="checked"' : ''; ?>> Operator Scan&nbsp;
                        <input type="radio" name="role" value="petugas" class="ml-3"  <?= $data['is_superadmin'] == 0 && $data['is_operator'] == 0 ? 'checked="checked"' : ''; ?>> Petugas&nbsp;
                        <div class="invalid-feedback">
                           <?= $validation->getError('role'); ?>
                     </div>
                     </div>

                     <button type="submit" class="btn btn-primary mt-4">Simpan</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<style>
    .toolbar {
        width: 100%;
        height: 40px;
        background-color: #f2f2f2;
        border-radius: 3px 3px 0 0
    }

    .toolbar .item {
        float: left;
        height: 40px;
        padding: 0 10px;
        line-height: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all .5s;
        width: 30px;
        text-align: center
    }

    .toolbar .item:first-child {
        border-radius: 3px 0 0 0
    }

    .toolbar .item:hover {
        background-color: #39065a;
        color: #fff
    }

    .toolbar+textarea {
        border-top: none;
        border-radius: 0 0 3px 3px
    }
</style>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Buat Broadcast Baru</b></h4>
          </div>
          <div class="card-body mx-5 my-3">

            <form action="<?= site_url('admin/broadcast/save'); ?>" method="post" enctype="multipart/form-data" class="form-default">
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

              

              <div class="form-group mt-0">
                <label for="judul">Judul Broadcast</label>
                <input name="judul" class="form-control" required id="judul" value="<?=old('judul')?>">
                
              </div>

              <div class="form-group mt-4">
                <label for="kelas">Group Kontak</label>
                <select name="kelas" id="kelas" class="form-control" required>
                    <option value="">--Pilih--</option>
                    <option value="gr">Guru & Staf</option>
                    <?php
                    foreach($kelas as $row){
                        ?>
                        <option value="<?=$row->id_kelas?>"><?=$row->kelas?> (<?=$row->jurusan;?>)</option>
                        <?php
                    }
                    ?>
                </select>
                
              </div>

              <div class="form-group mt-4">
                <label for="pesan">Pesan Broadcast</label>
                <div class="toolbar">
                    <div class="item" data-tool="bold">B</div>
                    <div class="item" data-tool="italic">I</div>
                    <div class="item" data-tool="striketrhough">S</div>
                </div>
                <textarea name="pesan" class="form-control" rows="10" required id="input-message"></textarea>
                <div class="alert alert-success mt-3">
                <b>KETERANGAN PARAMETER:</b><br>
                <b>UNTUK SISWA</b><br>
                <ul>
                  <li>[NISN] : memberikan NISN pada pesan.</li>
                  <li>[NAMA] : memberikan NAMA SISWA pada pesan.</li>
                  <li>[KELAS] : memberikan KELAS pada pesan.</li>
                  <li>[JURUSAN] : memberikan JURUSAN pada pesan.</li>
                </ul>
                <br>
                <b>UNTUK GURU / STAF</b><br>
                <ul>
                  <li>[NUPTK] : memberikan NUPTK pada pesan.</li>
                  <li>[NAMA] : memberikan NAMA SISWA pada pesan.</li>
                </ul>
              </div>
              </div>

              <div class="form-group mt-4">
                <label for="gambar">Gambar (Opsional)</label>
                <div class="input-group">
                           <input type="file" id="filefoto" class="form-control" name="filefoto" placeholder="1234" accept=".jpg, .jpeg, .png, .webp" />
                           <div class="invalid-feedback">
                           <?= $validation->getError('filefoto'); ?>
                        </div>
                    </div>
                        <small class="text-danger">File yang diijinkan JPG, JPEG, PNG, WEBP. Maksimal File : 200 Kb</small>
                

                </div>
              <div class="form-group mt-4">
                <label for="tanggal">Tanggal Kirim</label>
                <input name="tanggal" type="datetime-local" class="form-control" required id="tanggal" value="">
                
              </div>

              


              <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    var yourTextarea = document.getElementById("input-message");
    var insertAtCursor = function(myField, myValueBefore, myValueAfter) {

        if (document.selection) {

            myField.focus();
            document.selection.createRange().text = myValueBefore + document.selection.createRange().text + myValueAfter;


        } else if (myField.selectionStart || myField.selectionStart == '0') {

            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            myField.value = myField.value.substring(0, startPos) + myValueBefore + myField.value.substring(startPos, endPos) + myValueAfter + myField.value.substring(endPos, myField.value.length);

        }
    }

    $(document).ready(function(){
        $("#input-message").keydown(function(e) {
            if (e.ctrlKey) {
                if (e.keyCode == 66) {
                    insertAtCursor(yourTextarea, '*', '*');
                    return false;
                }
                if (e.keyCode == 73) {
                    insertAtCursor(yourTextarea, '_', '_');
                    return false;
                }
                if (e.keyCode == 83) {
                    insertAtCursor(yourTextarea, '~', '~');
                    return false;
                }
            }
        });

        $(".toolbar .item").click(function() {
            if ($(this).data("tool") == 'bold') {
                insertAtCursor(yourTextarea, '*', '*');
            }
            if ($(this).data("tool") == 'italic') {
                insertAtCursor(yourTextarea, '_', '_');
            }
            if ($(this).data("tool") == 'striketrhough') {
                insertAtCursor(yourTextarea, '~', '~');
            }
            $("#input-message").keyup();
        });
    });
</script>
    
<?= $this->endSection() ?>
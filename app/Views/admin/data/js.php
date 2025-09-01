<!--   Core JS Files   -->

<script src="<?= base_url('public/assets/js/core/jquery-3.5.1.min.js') ?>"></script>
   <script src="<?= base_url('public/assets/js/core/bootstrap.bundle.min.js') ?>"></script>
   <script src="<?= base_url('public/assets/js/core/popper.min.js') ?>"></script>
   <script src="<?= base_url('public/assets/js/core/bootstrap-material-design.min.js') ?>"></script>

<script src="<?= base_url('public/assets/js/plugins/perfect-scrollbar.jquery.min.js') ?>"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="<?= base_url('public/assets/js/plugins/nouislider.min.js') ?>"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= base_url('public/assets/js/material-dashboard.js') ?>" type="text/javascript"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

<script>
   
   $(document).ready(function() {
      $('.select2').select2();
      $('#tabledata').DataTable();
   });
   function kirimwa(){
      $(function(){
         $.ajax({
            url : '<?=url_to('kirimwa')?>',
            type :'GET',
            success : function(result){
               console.log(result);
            }
         })
      });
   }

   
   function kirimbroadcast(){
      $(function(){
         $.ajax({
            url : '<?=url_to('kirimbroadcast')?>',
            type :'GET',
            success : function(result){
               console.log(result);
            }
         })
      });
   }

   function kirimulangwa(){
      $(function(){
         $.ajax({
            url : '<?=url_to('kirimlagi')?>',
            type :'GET',
            success : function(result){
               console.log(result);
            }
         })
      });
   }


   setInterval(function() {
      kirimwa(),
      kirimbroadcast()     
   }, 10000);

   setInterval(function() {
      kirimulangwa()
   }, 50000);
   
   
</script>
</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      Developer by <b><a href="#">Istikomah</a></b>
    </div>
    <strong>Copyright &copy; 2020 <a href="#">JADWAL-SMANCA</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('assets/js/jquery.dataTables.js') ?>"></script>
<script src="<?php echo base_url('assets/js/dataTables.bootstrap4.js') ?>"></script>
<!--SweetAlert2 -->
<script src="<?php echo base_url("assets/js/sweetalert2.all.min.js") ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/js/adminlte.min.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/js/demo.js') ?>"></script>

<script>
  $(document).ready(function() {
    $("#datatables").DataTable();
    $('[data-toggle="tooltip"]').tooltip();
  })
</script>
</body>
</html>
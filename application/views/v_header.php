<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>JADWAL - SMANCA</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/fontawesome.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap4.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/adminlte.min.css') ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- jQuery -->
  <script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
</head>
<body class="hold-transition sidebar-mini layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto mr-5">
      <li class="nav-item dropdown">
        <a class="nav-link text-center pl-0 pr-0 ml-0 mr-5" data-toggle="tooltip" data-placement="left" title="About" href="<?php echo base_url('about') ?>">
          <i class="fa fa-info-circle fa-lg"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link text-center pl-0 pr-0 ml-0" data-toggle="tooltip" data-placement="left" title="Log Out" href="<?php echo base_url('logout') ?>">
          <i class="fa fa-sign-out-alt fa-lg"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('dashboard') ?>" class="brand-link text-center">
      <span class="brand-text font-weight-light">JADWAL - SMANCA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url('assets/img/profil.png') ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="pull-left info">
          <a style="color: white"><?php $akun = $this->session->userdata('admin'); ?>
          <?php echo $akun['nama_admin'] ?></a><br>
          <a href=""><i class="fas fa-check-circle"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo base_url('page') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'page' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt fa-lg"></i>
              <p> Dashboard </p>
            </a>
          </li>
           <li class="nav-item">
            <a href="<?php echo base_url('data_guru') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'data_guru' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-th-list fa-lg"></i>
              <p> Data Guru </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('data_kelas') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'nilaiatribut' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tasks fa-lg"></i>
              <p> Data Kelas </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('data_mapel') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'datatraining' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-database fa-lg"></i>
              <p> Data Mapel </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('data_ruang') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'datatesting' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chart-pie fa-lg"></i>
              <p> Data Ruang </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('data_hari') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'keputusan' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tree fa-lg"></i>
              <p> Data Hari </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('data_jam') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'keputusan' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tree fa-lg"></i>
              <p> Data Jam </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('data_jadwal') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'keputusan' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tree fa-lg"></i>
              <p> Jadwal </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
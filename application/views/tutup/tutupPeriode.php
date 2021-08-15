<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   Tutup Periode
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Tutup Periode</a></li>
    <li class="active">Tutup Periode</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
          <?php echo validation_errors(); ?>
          <form class="form-horizontal" id="tutupPeriode" action="<?php echo site_url('TutupPeriode/simpan'); ?>" method="POST">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                <div class="col-xs-5"></div>
                <div class="col-xs-3">
                  <button form="tutupPeriode" class="btn btn-danger" style="width: 200px">Tutup Periode</button>
                </div>
              </div>
              </div>
            </div>
          </form>
        </div>
      </div>

 </section>

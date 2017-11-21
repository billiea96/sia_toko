<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Pelunasan Nota Beli
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Pelunasan Nota Beli</a></li>
    <li class="active">Pelunasan Nota Beli</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <!-- Info boxes -->

  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
          <?php echo validation_errors(); ?>
          <form class="form-horizontal" id="form_pelunasanJual" action="<?php echo site_url('Penjualan/create_nota'); ?>" method="POST">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="noNota" class="col-xs-5 control-label">No Nota</label>

                  <div class="col-xs-2">
                    <input type="text" class="form-control"  disabled>
                    <input type="hidden" name="NoNotaJual">
                  </div>
                </div>

                <div class="form-group">
                  <label for="tgl" class="col-xs-5 control-label">Tanggal Pelunasan</label>

                  <div class="col-xs-3">
                    <div class="input-group date">
                      <input type="text" class="form-control pull-right" id="idTgl" name="tgl">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>

            	<div class="form-group">
              		<label for="jPembayaran" class="col-xs-5 control-label">Jenis Pembayaran</label>

              		<div class="col-xs-3">
                		<select form="form_pelunasanJual" class="form-control select2" id="idJPembayaran" name="jPembayaran">
                  		<option value="T">Tunai</option>
                  		<option value="TR">Transfer</option>
                		</select>
              		</div>
            	</div>

              <div class="form-group">
                  <label for="noRek" class="col-xs-5 control-label">No Rekening</label>

                  <div class="col-xs-2">
                    <input type="text" class="form-control" id="idNoRek" disabled>
                    <input type="hidden" name="noRek">
                  </div>
              </div>

              <div class="form-group">
                  <label for="namaPemilik" class="col-xs-5 control-label">Nama Pemilik Rekening</label>

                  <div class="col-xs-2">
                    <input type="text" class="form-control" id="idNamaPemilik"  disabled>
                    <input type="hidden" name="namaPemilik">
                  </div>
              </div>

            	<div class="form-group">
              		<label for="nominal" class="col-xs-5 control-label">Nominal Seharusnya</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDisplayNominal"  disabled="">
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
              		<label for="discPelunasan" class="col-xs-5 control-label">Diskon Pelunasan</label>
              
              		<div class="col-xs-3">
                		<div class="input-group">
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDiscPelunasan" name="discPelunasan" disabled="" placeholder="">
                  		<span class="input-group-addon">%</span>
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
              		<label for="totalBayar" class="col-xs-5 control-label">Total Pembayaran</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDisplayTotalBayar"  disabled="">
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
              		<label for="bayar" class="col-xs-5 control-label">Bayar</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_penjualan" type="number" min="0" onchange="hitungKembalian(this.value)" class="form-control" id="idBayar" name="bayar" placeholder="">
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
            		<div class="col-xs-5"></div>
            		<div class="col-xs-3">
    					<button form="form_pelunasanJual" type="submit" class="btn btn-success" style="width: 200px">Simpan</button>
    				</div>
    			</div>
    				
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->

<script>
$('#idJPembayaran').change(function(){
    if($(this).val() == "TR"){
      $('#idNoRek').removeAttr('disabled');
      $('#idNamaPemilik').removeAttr('disabled');
    }else{
      $('#idNoRek').attr('disabled', 'disabled');
      $('#idNamaPemilik').attr('disabled', 'disabled');
    }
  });
$(function () {
    // Date picker
    $('#idTgl').datepicker({
      format: 'yyyy-m-d',
      autoclose: true
    })
  })
</script>
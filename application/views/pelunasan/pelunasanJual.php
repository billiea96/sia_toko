<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Pelunasan Nota Jual
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Pelunasan Nota Jual</a></li>
    <li class="active">Pelunasan Nota Jual</li>
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
          <form class="form-horizontal" id="form_pelunasanJual" action="<?php echo site_url('PelunasanPiutang/simpan'); ?>" method="POST">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="noNota" class="col-xs-5 control-label">No Nota</label>

                  <div class="col-xs-2">
                    <select class="form-control select2" id="idNoNota" name="noNota">
                      <option></option>
                      <?php foreach ($NoNotaJual as $key => $value) { ?>
                      <option value="<?php echo $value['NoNotaJual'] ?>"><?php echo $value['NoNotaJual']; ?></option>
                      <?php } ?>
                    </select>
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
              		<label for="nominal" class="col-xs-5 control-label">Nominal Seharusnya</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDisplayNominal" disabled="">
                      <input type="hidden" id="nominal" name="nominal">
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
              		<label for="discPelunasan" class="col-xs-5 control-label">Diskon Pelunasan</label>
              
              		<div class="col-xs-2">
                		<div class="input-group">
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDiscPelunasan" disabled="" placeholder="">
                      <input type="hidden" id="discPelunasan" name="discPelunasan">
                  		<span class="input-group-addon">%</span>
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
              		<label for="totalBayar" class="col-xs-5 control-label">Total Pembayaran</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDisplayTotalBayar" disabled="">
                      <input type="hidden" id="totalBayar" name="totalBayar">
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
$('#idTgl').change(function(){
  var sekarang = $(this).val();
  $.ajax({
    url: '<?php echo site_url('PelunasanPiutang/detail_nota'); ?>',
    method: 'POST',
    dataType: 'json',
    data: {'noNota': $('#idNoNota').val()},
    success:function(data){
      if(new Date(sekarang) <= new Date(data.tgl)){
        $('#idDiscPelunasan').val(data.diskon);
        $('#idDisplayTotalBayar').val(data.totalBayar);
        
        $('#discPelunasan').val(data.diskon);
        $('#totalBayar').val(data.totalBayar);
        $('#idBayar').val(data.totalBayar);
      }else{
        $('#idDiscPelunasan').val(0);
        $('#idDisplayTotalBayar').val(data.sisaBayar);

        $('#discPelunasan').val(0);
        $('#totalBayar').val(data.sisaBayar);
        $('#idBayar').val(data.sisaBayar);
      }
    }
  })
});
$('#idNoNota').change(function(){
  $.ajax({
    url: '<?php echo site_url('PelunasanPiutang/detail_nota'); ?>',
    method: 'POST',
    dataType: 'json',
    data: {'noNota': $(this).val()},
    success:function(data){
      $('#idDisplayNominal').val(data.total);
      $('#idDiscPelunasan').val(data.diskon);
      $('#idDisplayTotalBayar').val(data.totalBayar);

      $('#nominal').val(data.total);
      $('#discPelunasan').val(data.diskon);
      $('#totalBayar').val(data.totalBayar);
      $('#idBayar').val(data.totalBayar);
    }
  })
})
$(function () {
    // Date picker
    $('#idTgl').datepicker({
      format: 'yyyy-m-d',
      autoclose: true
    })
  })
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })
</script>
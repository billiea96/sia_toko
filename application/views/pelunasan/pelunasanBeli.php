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
          <form class="form-horizontal" id="form_pelunasanJual" action="<?php echo site_url('PelunasanHutang/simpan'); ?>" method="POST">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="noPelunasan" class="col-xs-5 control-label">No Pelunasan</label>

                  <div class="col-xs-2">
                    <input type="text" class="form-control" value="<?php echo $NoPelunasan; ?>" disabled>
                    <input type="hidden" name="noPelunasan" value="<?php echo $NoPelunasan; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="noNota" class="col-xs-5 control-label">No Nota</label>

                  <div class="col-xs-2">
                    <select class="form-control select2" id="idNoNota" name="noNota">
                      <option></option>
                      <?php foreach ($NoNotaBeli as $key => $value) { ?>
                      <option value="<?php echo $value['NoNotaBeli'] ?>"><?php echo $value['NoNotaBeli']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="tgl" class="col-xs-5 control-label">Tanggal Pelunasan</label>

                  <div class="col-xs-3">
                    <div class="input-group date">
                      <input type="text" class="form-control pull-right" id="idTgl" name="tgl" value="<?php echo date('Y-m-d'); ?>" disabled>
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
                      <option value="C">Cek</option>
                		</select>
              		</div>
            	</div>
              <div class="form-group">
                  <label for="noCek" class="col-xs-5 control-label">No Cek</label>

                  <div class="col-xs-2">
                    <input type="text" class="form-control" id="idNoCek" name="noCek" disabled>
                  </div>
              </div>
              <div class="form-group">
                  <label for="bank" class="col-xs-5 control-label">Bank</label>

                  <div class="col-xs-2">
                    <select class="form-control select2" id="idBank" name="bank" disabled="">
                      <option></option>
                      <?php foreach ($bank as $key => $value) { ?>
                      <option value="<?php echo $value['IdBank'] ?>"><?php echo $value['Nama']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

              <div class="form-group">
                  <label for="noRek" class="col-xs-5 control-label">No Rekening</label>

                  <div class="col-xs-2">
                    <input type="text" class="form-control" id="idNoRek" name="noRek" disabled>
                  </div>
              </div>

              <div class="form-group">
                  <label for="namaPemilik" class="col-xs-5 control-label">Nama Pemilik Rekening</label>

                  <div class="col-xs-3">
                    <input type="text" class="form-control" id="idNamaPemilik" name="namaPemilik" disabled>
                  </div>
              </div>

            	<div class="form-group">
              		<label for="nominal" class="col-xs-5 control-label">Nominal Seharusnya</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDisplayNominal"  disabled="">
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
                  		<input form="form_pelunasanJual" type="number" min="0" class="form-control" id="idDisplayTotalBayar"  disabled="">
                      <input type="hidden" id="totalBayar" name="totalBayar">
                		</div>
              		</div>
            	</div>

            	<div class="form-group">
              		<label for="bayar" class="col-xs-5 control-label">Bayar</label>

              		<div class="col-xs-3">
                		<div class="input-group">
                  		<span class="input-group-addon">Rp.</span>
                  		<input form="form_penjualan" type="number" min="0" class="form-control" id="idBayar" name="bayar" placeholder="">
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
      $('#idBank').removeAttr('disabled');
      $('#idNoCek').attr('disabled', 'disabled');

    }else if($(this).val() == "T"){
      $('#idNoCek').attr('disabled', 'disabled');
      $('#idNoRek').attr('disabled', 'disabled');
      $('#idNamaPemilik').attr('disabled', 'disabled');
      $('#idBank').attr('disabled', 'disabled');
    }else{
      $('#idNoRek').attr('disabled', 'disabled');
      $('#idNamaPemilik').attr('disabled', 'disabled');
      $('#idBank').attr('disabled', 'disabled');
      $('#idNoCek').removeAttr('disabled');
    }
});



$('#idTgl').change(function(){
  var sekarang = $(this).val();
  $.ajax({
    url: '<?php echo site_url('PelunasanHutang/detail_nota'); ?>',
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
  var sekarang = '<?php echo date('Y-m-d') ?>';
  $.ajax({
    url: '<?php echo site_url('PelunasanHutang/detail_nota'); ?>',
    method: 'POST',
    dataType: 'json',
    data: {'noNota': $(this).val()},
    success:function(data){
      $('#idDisplayNominal').val(data.total);
      $('#nominal').val(data.total);
      

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
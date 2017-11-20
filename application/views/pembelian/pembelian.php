<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Pembelian
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Pembelian</a></li>
    <li class="active">Pembelian</li>
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
          <form class="form-horizontal">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="noNota" class="col-xs-4 control-label">No Nota</label>

                  <div class="col-xs-6">
                    <input type="text" class="form-control" id="noNota" name="nota" required="">
                  </div>
                </div>

                <div class="form-group">
                  <label for="tgl" class="col-xs-4 control-label">Tanggal</label>

                  <div class="col-xs-6">
                    <div class="input-group date">
                      <input type="text" class="form-control pull-right" id="idTgl" name="tgl" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="supplier" class="col-xs-4 control-label">Supplier</label>

                  <div class="col-xs-6">
                    <select class="form-control select2" id="idSupplier" name="supplier" required="">
                      <option></option>
                      <?php foreach ($supplier as $key => $value) { ?>
                      <option value="<?php echo $value['KodeSupplier'] ?>"><?php echo $value['Nama']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </form>

          <div class="row margin"></div>

          <form>
            <div class="row" style="padding-left: 100px; padding-right: 100px;">
              <div class="col-xs-1"></div>
              <div class="col-xs-4">
                <div class="form-group">
                  <center><label>Nama Barang</label></center>

                  <select class="form-control select2" id="idBarang" name="barang" required="">
                    <option></option>
                    <?php foreach ($barang as $key => $value) { ?>
                    <option value="<?php echo $value['KodeBarang'] ?>"><?php echo $value['Nama']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-xs-2">
                <div class="form-group">
                  <center><label>Jumlah</label></center>

                  <input type="number" min="0" class="form-control" id="idJumlah" name="jumlah" required>
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group">
                  <center><label>Harga</label></center>

                  <div class="input-group">
                    <span class="input-group-addon">Rp.</span>
                    <input type="number" min="0" class="form-control" id="idHarga" name="harga" required>
                  </div>
                </div>
              </div>
              <div class="col-xs-2">
                <div style="height: 20px; margin-bottom: 5px;"></div>
                <button id="tambahBarang" class="btn btn-info" style="width: 100px">+</button>
              </div>
            </div>
          </form>
          <form>
            <div class="row" style="padding-left: 100px; padding-right: 100px;">
              <div class="col-xs-10"></div>
              <div class="col-xs-2">
                <button type="submit" class="btn btn-danger" style="width: 100px">Clear</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"></h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-bordered" id="tableBarang">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Sub Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <div class="col-md-7">
            <label class="pull-right">Total</label>
          </div>
          <div class="col-md-5">
            <label>Rp.</label>
            <label id="totalHarga"></label>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal">
            <div class="form-group">
              <label for="jPembayaran" class="col-xs-4 control-label">Jenis Pembayaran</label>

              <div class="col-xs-6">
                <select class="form-control select2" id="idJPembayaran" name="jPembayaran">
                  <option value="T">Tunai</option>
                  <option value="TR">Transfer</option>
                  <option value="K">Kredit</option>
                  <option value="C">Cek</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="jt" class="col-xs-4 control-label">Tanggal Jatuh Tempo</label>

              <div class="col-xs-6">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="idJT" name="jt" disabled="">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="noRek" class="col-xs-4 control-label bank">No Rekening Bank</label>

              <div class="col-xs-6">
                <input type="text" class="form-control" id="idNoRek" name="noRek" disabled="">
              </div>
            </div>

            <div class="form-group">
              <label for="namaBank" class="col-xs-4 control-label bank">Bank</label>

              <div class="col-xs-6">
                <input type="text" class="form-control" id="idNamaBank" name="namaBank" disabled="">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal">
            <div class="form-group">
              <div class="col-xs-2"></div>
              <div class="checkbox col-xs-6">
                <label>
                  <input type="checkbox" id="idKirim" name="kirim">
                  <b>Barang Dikirim</b>
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="biayaKirim" class="col-xs-4 control-label">Biaya Kirim</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input type="number" min="0" class="form-control" id="idBiayaKirim" name="biayaKirim" disabled="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="discPelunasan" class="col-xs-4 control-label">Diskon Pelunasan</label>
              
              <div class="col-xs-4">
                <div class="input-group">
                  <input type="number" min="0" class="form-control" id="idDiscPelunasan" name="discPelunasan" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="batasPelunasan" class="col-xs-4 control-label">Batas Pelunasan</label>

              <div class="col-xs-6">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="idBatasPelunasan" name="batasPelunasan">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal">
            <div class="form-group">
              <label for="bayar" class="col-xs-4 control-label">Bayar</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input type="number" min="0" class="form-control" id="idBayar" name="bayar" placeholder="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="kembalian" class="col-xs-4 control-label">Kembalian</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input type="number" min="0" class="form-control" id="idKembalian" name="kembalian" disabled="">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    
    <button type="submit" class="btn btn-success pull-right margin" style="width: 200px">Simpan</button>
    
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script>

  $('#idJPembayaran').change(function(){
    if($(this).val() == "K"){
      $('#idJT').removeAttr('disabled');
    }else{
      $('#idJT').attr('disabled', 'disabled')  
    }
    if($(this).val() == "TR"){
      $('#idNoRek').removeAttr('disabled');
      $('#idNamaBank').removeAttr('disabled');
    }else{
      $('#idNoRek').attr('disabled', 'disabled');
      $('#idNamaBank').attr('disabled', 'disabled');
    }
  });

  $('#idKirim').click(function(){
    if($("#idKirim").is(':checked'))
      $('#idBiayaKirim').removeAttr('disabled');
  else
      $('#idBiayaKirim').attr('disabled', 'disabled');
  })
  var count = 0;
  var total = 0;
  var list = [];
  $('#tambahBarang').click(function(e){
    e.preventDefault()
    list[count] = [$('#idBarang').val(), $('#idJumlah').val(), $('#idHarga').val(), ($('#idHarga').val()*$('#idJumlah').val())]
    count+=1;
    $('#tableBarang tbody').append(
        '<tr>'+
        '<td>'+count+'</td>'+
        '<td>Gk Tau kode dapet dari mana</td>'+
        '<td>'+$('#idBarang').val()+'</td>'+
        '<td>'+$('#idJumlah').val()+'</td>'+
        '<td>'+$('#idHarga').val()+'</td>'+
        '<td data-sub='+($('#idHarga').val()*$('#idJumlah').val())+'>'+($('#idHarga').val()*$('#idJumlah').val())+'</td>'+
        '<td>Edit/Hapus</td>'+
        '</tr>'
      )
    total +=($('#idHarga').val()*$('#idJumlah').val())
    console.log(total.toString())
    $('#totalHarga').html(total.toLocaleString(['ban', 'id']))
  })
</script>

<script>
  // Selector
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })

  $(function () {
    // Date picker
    $('#idTgl').datepicker({
      autoclose: true
    })
  })
  $(function () {
    // Date picker
    $('#idJT').datepicker({
      autoclose: true
    })
  })
  $(function () {
    // Date picker
    $('#idBatasPelunasan').datepicker({
      autoclose: true
    })
  })
</script>

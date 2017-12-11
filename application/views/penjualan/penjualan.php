<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Penjualan
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Penjualan</a></li>
    <li class="active">Penjualan</li>
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
          <form class="form-horizontal" id="form_penjualan" action="<?php echo site_url('Penjualan/create_nota'); ?>" method="POST">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="noNota" class="col-xs-4 control-label">No Nota</label>

                  <div class="col-xs-6">
                    <input type="text" class="form-control" value="<?php echo $NoNotaJual; ?>" disabled>
                    <input type="hidden" name="NoNotaJual" value="<?php echo $NoNotaJual; ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label for="tgl" class="col-xs-4 control-label">Tanggal</label>

                  <div class="col-xs-6">
                    <div class="input-group date">
                      <input type="text" class="form-control pull-right" id="idTgl" name="tgl" value="<?php echo date('Y-m-d'); ?>" disabled>
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
                  <label for="customer" class="col-xs-4 control-label">Customer</label>

                  <div class="col-xs-6">
                    <select class="form-control select2" id="idCustomer" name="customer">
                      <option></option>
                      <?php foreach ($pelanggan as $key => $value) { ?>
                      <option value="<?php echo $value['KodePelanggan'] ?>"><?php echo $value['Nama']; ?></option>
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
              <div class="col-xs-2"></div>
              <div class="col-xs-4">
                <div class="form-group">
                  <center><label>Nama Barang</label></center>

                  <select class="form-control select2" id="idBarang" name="barang">
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

                  <input type="number" min="1" class="form-control" id="idJumlah" name="jumlah" placeholder="">
                </div>
              </div>
              <div class="col-xs-2">
                <div style="height: 20px; margin-bottom: 5px;"></div>
                <span onclick="add_cart()" class="btn btn-info" style="width: 100px">+</span>
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
          <table class="table table-hover table-bordered">
            <div align="right" class="col-xs-11 col-sm-offset-1">
              <button id="clear" class="btn btn-warning" style="width: 100px">Clear</button>
            </div>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Sub Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tbody">
            </tbody>            
          </table>
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
              <div class="col-xs-2"></div>
              <div class="checkbox col-xs-6">
                <label>
                  <input form="form_penjualan" type="checkbox" id="idKirim">
                  <input form="form_penjualan" type="hidden" id="idStatusKirim" name="kirim" value="false">
                  <b>Barang Dikirim</b>
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="biayaKirim" class="col-xs-4 control-label">Biaya Kirim</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input form="form_penjualan" type="number" min="0" class="form-control" id="idBiayaKirim" name="biayaKirim" disabled="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="fob" class="col-xs-4 control-label">Ditanggung Oleh</label>

              <div class="col-xs-6">
                <select form="form_penjualan" class="form-control select2" id="idFOB" name="fob" disabled="">
                  <option value=""></option>
                 <!--  <option value="FOB Shipping Point">FOB Shipping Point</option>
                  <option value="FOB Destination Point">FOB Destination Point</option> -->
                  <option value="FOB Shipping Point">Pembeli</option>
                  <option value="FOB Destination Point">Perusahaan</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="kurir" class="col-xs-4 control-label">Jasa Pengiriman</label>

              <div class="col-xs-6">
                <select form="form_penjualan" class="form-control select2" id="idKurir" name="kurir" disabled="">
                  <option value=""></option>
                  <!-- <option value="FOB Shipping Point">FOB Shipping Point</option>
                  <option value="FOB Destination Point">FOB Destination Point</option> -->
                  <option value="JNE">JNE</option>
                  <option value="TIKI">TIKI</option>
                  <option value="POS">Pos Indonesia</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="jPembayaranKirim" class="col-xs-4 control-label">Jenis Pembayaran Pengiriman</label>

              <div class="col-xs-6">
                <select form="form_penjualan" class="form-control select2" id="idJPembayaranKirim" name="jPembayaranKirim" disabled="">
                  <option value="T">Tunai</option>
                  <option value="TR">Transfer</option>
                </select>
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
              <label for="jPembayaran" class="col-xs-4 control-label">Jenis Pembayaran</label>

              <div class="col-xs-6">
                <select form="form_penjualan" class="form-control select2" id="idJPembayaran" name="jPembayaran">
                  <option value="T">Tunai</option>
                  <option value="TR">Transfer</option>
                  <option value="K">Kredit</option>
                  <option value="C">Cek</option>
                </select>
              </div>
            </div>
            <div class="form-group" id="idNomorCek" hidden="">
              <label for="nomorCek" class="col-xs-4 control-label">Nomor Cek</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <input form="form_penjualan" type="text" class="form-control" id="idNomorCek" >
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="bank" class="col-xs-4 control-label">Bank</label>

              <div class="col-xs-6">
                <select form="form_penjualan" class="form-control select2" id="idBank" name="bank" disabled>
                  <option value=""></option>
                  <?php foreach ($bank as $key => $value) { ?>
                    <option value="<?php echo $value['IdBank']; ?>"><?php echo $value['Nama']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>


            <div class="form-group">
              <label for="jt" class="col-xs-4 control-label">Tanggal Jatuh Tempo</label>

              <div class="col-xs-6">
                <div class="input-group date">
                  <input form="form_penjualan" type="text" class="form-control pull-right" id="idJT" name="jt" disabled="">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- Ini tak comment soalnya di mockup gk ada bank, kalo mau pake tinggal uncomment doang -->

            <!-- <div class="form-group">
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
            </div> -->

            <div class="form-group">
              <label for="discPelunasan" class="col-xs-4 control-label">Diskon Pelunasan</label>
              
              <div class="col-xs-4">
                <div class="input-group">
                  <input form="form_penjualan" type="number" min="0" class="form-control" id="idDiscPelunasan" name="discPelunasan" disabled="" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="batasPelunasan" class="col-xs-4 control-label">Batas Diskon Pelunasan</label>

              <div class="col-xs-6">
                <div class="input-group date">
                  <input form="form_penjualan" type="text" class="form-control pull-right" id="idBatasPelunasan" disabled="" name="batasPelunasan">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="disc" class="col-xs-4 control-label">Diskon</label>
              
              <div class="col-xs-4">
                <div class="input-group">
                  <input form="form_penjualan" type="number" min="0" class="form-control" id="idDisc" name="disc" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="ppn" class="col-xs-4 control-label">PPN</label>
              
              <div class="col-xs-4">
                <div class="input-group">
                  <input form="form_penjualan" type="number" min="0" class="form-control" id="idPPN" name="ppn" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="totalBayar" class="col-xs-4 control-label">Total Bayar</label>
              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input form="form_pembelian" type="text" min="0" class="form-control" id="idTotalBayar"  disabled="">
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
                  <input form="form_penjualan" type="number" min="0" onchange="hitungKembalian(this.value)" class="form-control" id="idBayar" name="bayar" placeholder="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="kembalian" class="col-xs-4 control-label">Kembalian</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input form="form_penjualan" type="number" min="0" class="form-control" id="idDisplayKembalian"  disabled="">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    
    <button form="form_penjualan" type="submit" class="btn btn-success pull-right margin" style="width: 200px">Simpan</button>
    
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script>
  $('#idJPembayaran').change(function(){
    if($(this).val() == "K"){
      $('#idJT').removeAttr('disabled');
      $('#idDiscPelunasan').removeAttr('disabled');
      $('#idBatasPelunasan').removeAttr('disabled');
      $('#idBank').removeAttr('disabled');
      $('#idNomorCek').attr('hidden', ' hidden');
    }else{
      $('#idJT').attr('disabled', 'disabled');  
      $('#idDiscPelunasan').attr('disabled', 'disabled');
      $('#idBatasPelunasan').attr('disabled', 'disabled');
      $('#idBank').attr('disabled', 'disabled');
      $('#idNomorCek').attr('hidden', ' hidden');
    }
    if($(this).val() == "TR"){
      $('#idNoRek').removeAttr('disabled');
      $('#idNamaBank').removeAttr('disabled');
      $('#idBank').removeAttr('disabled');
      $('#idNomorCek').attr('hidden', ' hidden');
    }else{
      $('#idNoRek').attr('disabled', 'disabled');
      $('#idNamaBank').attr('disabled', 'disabled');
      
    }
    if($(this).val() == "C"){
     $('#idNomorCek').removeAttr('hidden');
   }
  });

  $('#idKirim').click(function(){
    if($("#idKirim").is(':checked')){
      document.getElementById('idStatusKirim').value = 'true';
      $('#idBiayaKirim').removeAttr('disabled');
      $('#idFOB').removeAttr('disabled');
      $('#idKurir').removeAttr('disabled');
      $('#idJPembayaranKirim').removeAttr('disabled');
    }
    else{
      $('#idBiayaKirim').attr('disabled', 'disabled');
      document.getElementById('idStatusKirim').value = 'false';
      $('#idFOB').attr('disabled', 'disabled');
      $('#idKurir').attr('disabled', 'disabled');
      $('#idJPembayaranKirim').attr('disabled', 'disabled');
      }     
    });

  function add_cart(){
    var id_barang = document.getElementById('idBarang').value;
    var jumlah = document.getElementById('idJumlah').value;    

    $.ajax({
      url:"<?php echo site_url('Penjualan/add_cart'); ?>",
      method:'POST',
      data:{'id_barang':id_barang,'jumlah':jumlah},
      success:function(data){
        alert('Barang ditambahkan dalam keranjang');
        $('#tbody').html(data);
        $('#idTotalBayar').val($('#idTotal').val());
      },
      error:function(){
        alert('Gagal tambah barang ke keranjang');
      }
    });

  }

  $('#tbody').load('<?php echo site_url('Penjualan/load') ?>');

  $(document).on('click','.hapus-barang',function(){
    var row_id = $(this).attr("id");
    if(confirm("Ingin hapus barang ini?")){
      $.ajax({
        url:'<?php echo site_url("Penjualan/remove") ?>',
        method:'POST',
        data:{'row_id':row_id},
        success:function(data){
          alert('Barang berhasil dihapus');
          $('#tbody').html(data);
        },
        error:function(){
          alert('Gagal menghapus barang');
        }
      });
    } 
    else{
      return false;
    }
  });

  $(document).on('click','#clear',function(){
    if(confirm('Ingin menghapus semua barang dikeranjang?')){
      $.ajax({
        url:'<?php echo site_url('Penjualan/clear_cart'); ?>',
        success:function(data){
          alert('Semua barang dalam keranjang dihapus');
          $('#tbody').html(data);
        },
        error:function(){
          alert('Gagal menghapus semua barang dalam keranjang');
        }
      });
    }
    else{
      return false;
    }
  });
  
  function hitungKembalian(value){

  }
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
      format: 'yyyy-m-d',
      autoclose: true
    })
  })
  $(function () {
    // Date picker
    $('#idJT').datepicker({
      format: 'yyyy-m-d',
      autoclose: true
    })
  })
  $(function () {
    // Date picker
    $('#idBatasPelunasan').datepicker({
      format: 'yyyy-m-d',
      autoclose: true
    })
  })
</script>

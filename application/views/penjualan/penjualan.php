<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Penjualan
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Penjualan</a></li>
    <li class="active">penjualan</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <!-- Info boxes -->

  <div class="row">
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Barang</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <?php echo validation_errors(); ?>
        <form class="form-horizontal" id="form_barang" action="#">
          <div class="box-body">
            <div class="form-group">
              <label for="noNota" class="col-xs-4 control-label">No Nota</label>

              <div class="col-xs-6">
                <input type="text" class="form-control" id="noNota" name="nota" placeholder="">
              </div>
            </div>

            <div class="form-group">
              <label for="pelanggan" class="col-xs-4 control-label">Nama Customer</label>

              <div class="col-xs-6">
                <select class="form-control select2" id="idPelanggan" name="pelangan">
                  <option></option>
                  <?php foreach ($pelanggan as $key => $value) { ?>
                  <option value="<?php echo $value['KodePelanggan'] ?>"><?php echo $value['Nama']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="barang" class="col-xs-4 control-label">Barang</label>

              <div class="col-xs-6">
                <select class="form-control select2" id="idBarang" name="barang">
                  <option></option>
                  <?php foreach ($barang as $key => $value) { ?>
                  <option value="<?php echo $value['KodeBarang'] ?>"><?php echo $value['Nama']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="noNota" class="col-xs-4 control-label">Jumlah</label>

              <div class="col-xs-4">
                <input type="number" min="0" class="form-control" id="idJumlah" name="jumlah" placeholder="">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-xs-4 control-label"></label>

              <div class="col-xs-6">
                <!-- + diganti sama icon + -->
                <button type="submit" class="btn btn-info" style="width: 100px">+</button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Pembayaran</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <?php echo validation_errors(); ?>
        <form class="form-horizontal" id="form_pembayaran" action="#">
          <div class="box-body">
            <div class="form-group">
              <label for="jenisPembayaran" class="col-xs-4 control-label">Jenis Pembayaran</label>

              <div class="col-xs-6">
                <select class="form-control select2" id="idJPembayaran" name="jenisPembayaran">
                  <option></option>
                  <!-- <?php foreach ($pelanggan as $key => $value) { ?>
                  <option value="<?php echo $value['KodePelanggan'] ?>"><?php echo $value['Nama']; ?></option>
                  <?php } ?> -->
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="discLangsung" class="col-xs-4 control-label">Diskon Langsung</label>
              
              <div class="col-xs-4">
                <div class="input-group">
                  <input type="number" min="0" class="form-control" id="idDiscLangsung" name="discLangsung" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="discTakLangsung" class="col-xs-4 control-label">Diskon tak Langsung</label>

              <div class="col-xs-4">
                <div class="input-group">
                  <input type="number" min="0" class="form-control" id="idDiscTakLangsung" name="discTakLangsung" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="ppn" class="col-xs-4 control-label">PPN</label>

              <div class="col-xs-4">
                <div class="input-group">
                  <input type="number" min="0" class="form-control" id="idPPN" name="ppn" placeholder="">
                  <span class="input-group-addon">%</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="jt" class="col-xs-4 control-label">Tanggal Jatuh Tempo</label>

              <div class="col-xs-6">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="idJT" name="jt">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
              <!-- /.input group -->
            </div>

            <div class="form-group">
              <label for="bdPelunasan" class="col-xs-4 control-label">Batas Diskon Pelunasan</label>

              <div class="col-xs-6">
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" id="idBDPelunasan" name="bdPelunasan">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
              <!-- /.input group -->
            </div>

            <div class="form-group">
              <label for="totalHarga" class="col-xs-4 control-label">Total Harga</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input type="number" min="0" class="form-control" id="idTotalHarga" name="totalHarga" placeholder="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="jasaPengiriman" class="col-xs-4 control-label">Jasa Pengiriman</label>

              <div class="col-xs-6">
                <select class="form-control select2" id="idJasaPengiriman" name="jasaPengiriman">
                  <option></option>
                  <!-- <?php foreach ($pelanggan as $key => $value) { ?>
                  <option value="<?php echo $value['KodePelanggan'] ?>"><?php echo $value['Nama']; ?></option>
                  <?php } ?> -->
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="biayaKirim" class="col-xs-4 control-label">Biaya Kirim</label>

              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input type="number" min="0" class="form-control" id="idBiayaKirim" name="biayaKirim" placeholder="">
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>


      <!-- <?php echo validation_errors(); ?>
      <form class="form-horizontal" id="form_penjualan" action="<?php echo site_url('Penjualan/add') ?>">
        <div class="box-body">
          <div class="form-group">
            <label class="control-label col-sm-3 col-sm-offset-8" for="date" name="date" style="font-size: 20px;"><?php echo date('D, d M Y'); ?></label>
          </div>
          <div class="form-group">
            <label for="pelanggan" class="col-sm-4 control-label">Pelanggan : </label>
            <div class="col-sm-3">
              <select class="form-control select2" id="pelanggan" name="pelangan">
                <option></option>
                <?php foreach ($pelanggan as $key => $value) { ?>
                <option value="<?php echo $value['KodePelanggan'] ?>"><?php echo $value['Nama']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="barang" class="col-sm-4 control-label">Barang : </label>
            <div class="col-sm-3">
              <select class="form-control select2" id="barang" name="barang">
                <option></option>
                <?php foreach ($barang as $key => $value) { ?>
                <option value="<?php echo $value['KodeBarang'] ?>"><?php echo $value['Nama']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </form> -->
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">List</h3>

          <button type="submit" class="btn btn-danger pull-right " style="width: 200px">Clear</button>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-bordered">
            <tbody>
              <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Sub Total</th>
                <th>Action</th>
              </tr>
              <tr>
                <td>1</td>
                <td>AAA-111</td>
                <td>Pensil</td>
                <td>10 pcs</td>
                <td>Rp.-00</td>
                <td>Rp.-00</td>
                <td>Edit/Hapus</td>
              </tr>
              <tr>
                <td>2</td>
                <td>AAA-222</td>
                <td>Buku</td>
                <td>12 pcs</td>
                <td>Rp.-00</td>
                <td>Rp.-00</td>
                <td>Edit/Hapus</td>
              </tr>
              <tr>
                <td colspan="5" align="right">Total</td>
                <td colspan="2" align="center">Rp.-00</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
  <div class="row">
    
    <button type="submit" class="btn btn-success pull-right margin" style="width: 200px">Simpan dan Cetak</button>
    <button type="submit" class="btn btn-success pull-right margin" style="width: 200px">Simpan</button>
    
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })
</script>

<script>
  $(function () {
    //Date picker
    $('#idJT').datepicker({
      autoclose: true
    })
  })
</script>

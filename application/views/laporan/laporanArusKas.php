<?php
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
  
    // set document information
    $pdf->SetCreator(PB-AOF);
    $pdf->SetAuthor('PB-AOF');
    $pdf->SetTitle('Laporan Arus Kas');
    $pdf->SetSubject('Laporan Arus Kas');
    $pdf->SetKeywords('');   
  
    // // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LABA_POS, PDF_HEADER_NERACA, PDF_HEADER_STRING);
    // $pdf->setFooterData(array(0,64,0), array(0,64,128)); 
  	
    // set header and footer fonts
    // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
  
    // set default monospaced font
    // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
  
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);    
  
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
    
  
    // set default font subsetting mode
    $pdf->setFontSubsetting(true);   
  
    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9, '', true);   
  
    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage(); 
  
    // set text shadow effect
    $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));    
  	$table .= '<h3 style="text-align:left;">Saldo Awal</h3>';
  	$table .= '<br><h4 style="text-align:left;">Kas di Tangan : Rp. '.$kas[0]['SaldoAwal'].'</h4>';
  	$table .= '<br><h4 style="text-align:left;">Rekening Bank Baca Baca : Rp. '.$rekeningBacaBaca[0]['SaldoAwal'].'</h4>';
  	$table .= '<br><h4 style="text-align:left;">Rekening Bank Suka Sendiri : Rp. '.$rekeningSukaSendiri[0]['SaldoAwal'].'</h4>';
  	$table .= '<h3 style="text-align:left;">Arus Kas Kegiatan Operasional</h3>';
	$table .='<table style="width:560px;">';
	$table .='<thead style="width:560px; ">
            <tr>
    		<th style="border:1px solid #000; width: 200px; text-align: center">Nama Akun</th>
    		<th style="border:1px solid #000; width: 60px; text-align: center">Saldo Akhir</th>

	<!-- Main content -->
	<section class="content">
	  <!-- Info boxes -->

    		</tr>
            </thead>';
            $total = 0;
    		foreach($kas as $key=>$value){
    			if($key == 0){
    			
    			}
	    		$table .= '<tr> 
	    		<td style="border:1px solid #000; width: 200px; height: 20px;">'.$value["Keterangan"].'</td>';
	    		if($value["NominalDebet"] != 0)
	    		{
	    			$table .='<td style="border:1px solid #000; width: 60px; height: 20px;">'.$value["NominalDebet"].'</td>';
	    			$total += $value["NominalDebet"];
	    		}
	    		if($value["NominalKredit"] != 0)
	    		{
	    			$table .='<td style="border:1px solid #000; width: 60px; height: 20px;">'.($value["NominalKredit"]*-1).'</td>';
	    			$total += ($value["NominalKredit"]*-1);
	    		}
	    			
				
	    		$table .=' </tr>';
    		}

    		foreach($rekeningBacaBaca as $key=>$value){
    			if($key == 0){
    			
    			}
	    		$table .= '<tr> 
	    		<td style="border:1px solid #000; width: 200px; height: 20px;">'.$value["Keterangan"].'</td>';
	    		if($value["NominalDebet"] != 0)
	    		{
	    			$table .='<td style="border:1px solid #000; width: 60px; height: 20px;">'.$value["NominalDebet"].'</td>';
	    			$total += $value["NominalDebet"];
	    		}
	    		if($value["NominalKredit"] != 0)
	    		{
	    			$table .='<td style="border:1px solid #000; width: 60px; height: 20px;">'.($value["NominalKredit"]*-1).'</td>';
	    			$total += ($value["NominalKredit"]*-1);
	    		}
	    			
						
	    		$table .=' </tr>';
    		}

    		foreach($rekeningSukaSendiri as $key=>$value){
    			if($key == 0){
    			
    			}
	    		$table .= '<tr> 
	    		<td style="border:1px solid #000; width: 200px; height: 20px;">'.$value["Keterangan"].'</td>';
	    		if($value["NominalDebet"] != 0)
	    		{
	    			$table .='<td style="border:1px solid #000; width: 60px; height: 20px;">'.$value["NominalDebet"].'</td>';
	    			$total += $value["NominalDebet"];
	    		}
	    		if($value["NominalKredit"] != 0)
	    		{
	    			$table .='<td style="border:1px solid #000; width: 60px; height: 20px;">'.($value["NominalKredit"]*-1).'</td>';
	    			$total += ($value["NominalKredit"]*-1);
	    		}
	    			
	    		$table .=' </tr>';
    		}

    		$table .='<tr> <td style="border:1px solid #000; width: 200px; height: 20px; font-weight:bold;">Total Arus Kas dari Kegiatan Operasional : </td>
    		<td style="border:1px solid #000; width: 60px; height: 20px; font-weight:bold;">'.$total.'</td></tr>';
    
	$table .='</tbody></table>';	

	$table .= '<br><h4 style="text-align:left;">Kas di Tangan : Rp. '.$kasAkhir[0]['SaldoAkhir'].'</h4>';
  	$table .= '<br><h4 style="text-align:left;">Rekening Bank Baca Baca : Rp. '.$rekeningBacaBacaAkhir[0]['SaldoAkhir'].'</h4>';
  	$table .= '<br><h4 style="text-align:left;">Rekening Bank Suka Sendiri : Rp. '.$rekeningSukaSendiriAkhir[0]['SaldoAkhir'].'</h4>';								
     
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 0, 0, true, 'C', true);   
 	$pdf->LastPage();
    // ---------------------------------------------------------    
  
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    ob_clean();
    $pdf->Output('LaporanNeraca.pdf', 'I');    
  
    //============================================================+
    // END OF FILE
    //============================================================+
			

?>
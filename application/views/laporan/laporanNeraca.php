<?php
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
  
    // set document information
    $pdf->SetCreator(PB-AOF);
    $pdf->SetAuthor('PB-AOF');
    $pdf->SetTitle('Laporan Neraca');
    $pdf->SetSubject('Laporan Neraca');
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
  	
  	$table .= '<h3 style="text-align:left;">Aktiva(Aset)</h3>';
	$table .='<table style="width:560px;">';
	$table .='<thead style="width:560px; ">
            <tr>
    		<th style="border:1px solid #000; width: 200px; text-align: center">Nama Akun</th>
    		<th style="border:1px solid #000; width: 120px; text-align: center">Saldo Akhir</th>
	<!-- Main content -->
	<section class="content">
	  <!-- Info boxes -->

    		</tr>
            </thead>';
            $total=0;
    		foreach($aktiva as  $value){
    			$total+=$value["SaldoAkhir"];			
				$table.='<tr tr style="width:560px;">
		
					<td style="border:1px solid #000; width: 200px;">'.$value["Nama"].'</td>
					<td style="border:1px solid #000; width: 120px;">'.$value["SaldoAkhir"].'</td>
				</tr>';
    		}
    		$table .= '<tr> 
    					<td style="border:1px solid #000; font-weight:bold; width: 200px;"> Total Aktiva(Aset)</td>
    					<td style="border:1px solid #000; font-weight:bold; width: 120px;"> '.$total.'</td>
    				   </tr>';
    			
    
	$table .='</tbody></table>';
    

    $table .= '<h3 style="text-align:left;">Pasiva</h3>';

 
    $table .='<br><br><table style="width:560px;">';
	$table .='<thead style="width:560px; ">
            <tr>
    		<th style="border:1px solid #000; width: 200px; text-align: center">Nama Akun</th>
    		<th style="border:1px solid #000; width: 120px; text-align: center">Saldo Akhir</th>
	<!-- Main content -->
	<section class="content">
	  <!-- Info boxes -->

    		</tr>
            </thead>';
            $total=0;
            $totalModal =0;
    		foreach($pasiva as  $value){
                if($value['NoAkun']=='301'){
                    $totalModal=-1*$value['SaldoAkhir']+($totalPendapatan['TotalPendapatan']-$totalBiaya['TotalBiaya']);
                    $total+=$totalModal;          
                    $table.='<tr tr style="width:560px;">
            
                        <td style="border:1px solid #000; width: 200px;">'.$value["Nama"].'</td>
                        <td style="border:1px solid #000; width: 120px;">'.$totalModal.'</td>
                    </tr>';
                }else{
        			$total+=($value["SaldoAkhir"]*-1);			
    				$table.='<tr tr style="width:560px;">
    		
    					<td style="border:1px solid #000; width: 200px;">'.$value["Nama"].'</td>
    					<td style="border:1px solid #000; width: 120px;">'.($value["SaldoAkhir"]*-1).'</td>
    				</tr>';
                }
    		}
    		$table .= '<tr> 
    					<td style="border:1px solid #000; font-weight:bold; width: 200px;"> Total Pasiva(Aset)</td>
    					<td style="border:1px solid #000; font-weight:bold; width: 120px;"> '.$total.'</td>
    				   </tr>';
    			
    
	$table .='</tbody></table>';  
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
<?php
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
  
    // set document information
    $pdf->SetCreator(PB-AOF);
    $pdf->SetAuthor('PB-AOF');
    $pdf->SetTitle('Laporan Perubahan Ekuitas');
    $pdf->SetSubject('Laporan Perubahan Ekuitas');
    $pdf->SetKeywords('');   
  
    // // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_EKUITAS_POS, PDF_HEADER_PERUBAHAN_EKUITAS, PDF_HEADER_STRING);
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
  
    // Set some content to print
   
// Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true); 


	$table ='<table style="width:560px;">';
	$table .='<thead style="width:560px;">
            <tr>
    		<th style="border:1px solid #000; width: 200px; text-align: center;">Nama Akun</th>
    		<th style="border:1px solid #000; width: 60px; text-align: center;">Saldo Akhir</th>
    		
	<!-- Main content -->
	<section class="content">
	  <!-- Info boxes -->

    		</tr>
            </thead>';
    		
			$table.='<tr style="width:560px;">
				<td style="border:1px solid #000; width: 200px;">Ekuitas Pemilik per Periode ini</td>
				<td style="border:1px solid #000; width: 60px;">'.$vperubahanekuitas[0]["SaldoAkhir"].'</td>
				
			</tr>';
                
            $table.='<tr style="width:560px;">
                    <td style="border:1px solid #000; width: 200px;">Laba Rugi Tahun Berjalan</td>
                    <td style="border:1px solid #000; width: 60px;">'.$labarugi.'</td>
                    
                </tr>';

            $table.='<tr style="width:560px;">
                    <td style="border:1px solid #000; width: 200px;">'.$vperubahanekuitas[1]["NamaAkun"].'</td>
                    <td style="border:1px solid #000; width: 60px;">'.$vperubahanekuitas[1]["SaldoAkhir"].'</td>
                    
                </tr>';
            $table.='<tr style="width:560px;">
                    <td style="border:1px solid #000; font-weight:bold; width: 200px;">Ekuitas Akhir Pemilik</td>
                    <td style="border:1px solid #000; font-weight:bold; width: 60px;">'.($vperubahanekuitas[0]["SaldoAkhir"]+$labarugi).'</td>
                    
                </tr>';
    			    
	$table .='</tbody></table>';
      
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 0, 0, true, 'C', true);   
 	$pdf->LastPage();
    // ---------------------------------------------------------    
  
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    ob_clean();
    ob_flush();
    $pdf->Output('LaporanPerubahanEkuitas.pdf', 'I');
    ob_end_flush();
    ob_end_clean();     
  
    //============================================================+
    // END OF FILE
    //============================================================+

?>
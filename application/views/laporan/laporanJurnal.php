<?php
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
  
    // set document information
    $pdf->SetCreator(PB-AOF);
    $pdf->SetAuthor('PB-AOF');
    $pdf->SetTitle('Laporan Jurnal');
    $pdf->SetSubject('Laporan Jurnal');
    $pdf->SetKeywords('');   
  
    // // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_JURNAL_POS, PDF_HEADER_JURNAL, PDF_HEADER_STRING);
    // $pdf->setFooterData(array(0,64,0), array(0,64,128)); 
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
  
// Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true); 

    $table ='<table style="margin-left:300px;">';
    $table .='<thead style="width:560px;">
            <tr>
            <th style="border:1px solid #000; width: 60px; text-align: center">Tanggal</th>
            <th style="border:1px solid #000; width: 170px; text-align: center">Keterangan</th>
            <th style="border:1px solid #000; width: 120px; text-align: center">Nama Akun</th>
            <th style="border:1px solid #000; width: 80px; text-align: center">Debet</th>
            <th style="border:1px solid #000; width: 80px; text-align: center">Kredit</th>
            </tr>
            </thead>';
    $table .='<tbody>';
            $tampung = '';
            $hitung = sizeof($vlaporanjurnal)-1;
            foreach($vlaporanjurnal as $key=>$value){
                if($tampung == $value['IDJurnal']){
                    
                        if($key == $hitung){
                            $table.='<tr style="width:560px;">
                                <td style="border-right:1px solid #000; border-bottom:1px solid #000; border-left:1px solid #000; width:60px;"></td>
                                <td style="border-right:1px solid #000; border-bottom:1px solid #000; width:170px"></td>
                                <td style="border:1px solid #000; width:120px;">'.$value["NamaAkun"].'</td>
                                <td style="border:1px solid #000; width:80px;">'.$value["Debet"].'</td>
                                <td style="border:1px solid #000; width:80px;">'.$value["Kredit"].'</td>
                            </tr>';
                        }else{
                            $table.='<tr style="width:560px;">
                                <td style="border-right:1px solid #000; border-left:1px solid #000; width:60px;"></td>
                                <td style="border-right:1px solid #000; width:170px"></td>
                                <td style="border:1px solid #000; width:120px;">'.$value["NamaAkun"].'</td>
                                <td style="border:1px solid #000; width:80px;">'.$value["Debet"].'</td>
                                <td style="border:1px solid #000; width:80px;">'.$value["Kredit"].'</td>
                            </tr>';
                        }

                }else{
                    $tampung = $value['IDJurnal'];
                    $table.='<tr style="margin-top:50px">
                            <td style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; width:60px;">'.$value["Tanggal"].'</td>
                            <td style="border-top:1px solid #000; border-right:1px solid #000; width:170px;">'.$value["Keterangan"].'</td>
                            <td style="border:1px solid #000; width:120px;">'.$value["NamaAkun"].'</td>
                            <td style="border:1px solid #000; width:80px;">'.$value["Debet"].'</td>
                            <td style="border:1px solid #000; width:80px;">'.$value["Kredit"].'</td>
                        </tr>';
                }
                
            }
    $table .='</tbody></table>';
      
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, 'C', true);   
  
    // Print text using writeHTMLCell()
  
    $pdf->LastPage();
    // ---------------------------------------------------------    
  
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    ob_clean();
    $pdf->Output('LaporanJurnal.pdf', 'I');    
   
  
    //============================================================+
    // END OF FILE
?>
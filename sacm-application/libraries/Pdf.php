<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Heruno
 *  License    : Protected 
 *  Email      : wolu.88@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */  
require_once APPPATH."/third_party/PHPPdf/fpdf.php"; 
 
class  Pdf extends FPDF  { 
    public function __construct() { 
        parent::__construct(); 
    } 
}
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
include_once APPPATH.'/third_party/mpdf/mpdf.php';
 
class M_pdf {
 
    public $param;
    public $pdf;
 /**
 * Create a new PDF document
 *
 * @param string $mode
 * @param string $format
 * @param int $font_size
 * @param string $font
 * @param int $margin_left
 * @param int $margin_right
 * @param int $margin_top (Margin between content and header, not to be mixed with margin_header - which is document margin)
 * @param int $margin_bottom (Margin between content and footer, not to be mixed with margin_footer - which is document margin)
 * @param int $margin_header
 * @param int $margin_footer
 * @param string $orientation (P, L)
 */
    //public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    public function __construct($param = '"utf-8","A4","","",10,10,100,50,6,3')
    {
        $this->param =$param;
        $this->pdf = new mPDF('"utf-8","A4","","",10,10,20,10,6,3');
    }
}
?>
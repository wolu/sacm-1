<?php defined('BASEPATH') or exit('No direct script access allowed');
class Export extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }
    function index()
    {
        $this->template->load('frontend', 'export');
    }
    function pdf()
    {
        $this->load->library('pdf');
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(189, 15, 'LEMBAR DISPOSISI', 0, 1, "C");

        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(63, 25, 'KK', 1, 0, "C");
        $this->pdf->Cell(63, 25, 'Kode Klasifikasi', 1, 0, "C");
        $this->pdf->Cell(63, 25, 'Index', 1, 1, "C");

        $this->pdf->Cell(189, 15, 'Prihal :', 1, 1, "L");
        $this->pdf->Cell(95, 25, 'No/Tgl :', 1, 0, "L");
        $this->pdf->Cell(94, 25, 'Tgl :', 1, 1, "L");

        $this->pdf->Cell(63, 15, 'Kepada', 1, 0, "L");
        $this->pdf->Cell(63, 15, 'Isi', 1, 0, "C");
        $this->pdf->Cell(63, 15, 'Paraf/Tgl', 1, 1, "R");

        $this->pdf->Cell(189, 140, '', 1, 1);
        $this->pdf->Output();
    }
    function membuatpdf(){
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial','',12);
        $teks = "Cara Gampang Integrasi FPDF dengan Codeigniter";
        // mencetak 10 baris kalimat dalam variable "teks".
        for( $i=0; $i < 10; $i++ ) {
            $pdf->Cell(0, 5, $teks, 1, 1, 'L'); 
        }
        $this->pdf->Output();
    }
    public function report()
    {
        $this->load->library('pdf');
        /* buat konstanta dengan nama FPDF_FONTPATH, kemudian kita isi value-nya
        dengan alamat penyimpanan FONTS yang sudah kita definisikan sebelumnya.
        perhatikan baris $config['fonts_path']= 'system/fonts/'; 
        didalam file application/config/config.php
        */
        $this->load->model('master/karyawan_model', 'karyawan_model');
        $karyawan = $this->karyawan_model->get_all();
        /* setting zona waktu */
        date_default_timezone_set('Asia/Jakarta');
        /* konstruktor halaman pdf sbb :
        P  = Orientasinya "Potrait"
        cm = ukuran halaman dalam satuan centimeter
        A4 = Format Halaman
        jika ingin mensetting sendiri format halamannya, gunakan array(width, height)  
        contoh : $this->pdf->FPDF("P","cm", array(20, 20));  
        */
        $this->pdf->FPDF("P", "cm", "A4");
        // kita set marginnya dimulai dari kiri, atas, kanan. jika tidak diset, defaultnya 1 cm
        $this->pdf->SetMargins(1, 1, 1);
        /* AliasNbPages() merupakan fungsi untuk menampilkan total halaman
        di footer, nanti kita akan membuat page number dengan format : number page / total page
        */
        $this->pdf->AliasNbPages();

        // AddPage merupakan fungsi untuk membuat halaman baru
        $this->pdf->AddPage();

        // Setting Font : String Family, String Style, Font size
        $this->pdf->SetFont('Times', 'B', 12);

        /* Kita akan membuat header dari halaman pdf yang kita buat
        -------------- Header Halaman dimulai dari baris ini -----------------------------
        */
        $this->pdf->Cell(19, 0.7, 'PT. KRAKATAU STEEL (Persero) Tbk', 0, 0, 'C');
        // fungsi Ln untuk membuat baris baru
        $this->pdf->Ln();
        $this->pdf->Cell(19, 0.7, 'System Asset Configuration Management ', 0, 0, 'C');
        $this->pdf->Ln();
        /* 
        Setting ulang Font : String Family, String Style, Font size
        kenapa disetting ulang ???
        jika tidak disetting ulang, ukuran font akan mengikuti settingan sebelumnya.
        tetapi karena kita menginginkan settingan untuk penulisan alamatnya berbeda,
        maka kita harus mensetting ulang Font nya.
        jika diatas settingannya : helvetica, 'B', '12'
        khusus untuk penulisan alamat, kita setting : helvetica, '', 10
        yang artinya string stylenya normal / tidak Bold dan ukurannya 10 
        */
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(19, 0.5,
            'Jl. xxxxxxxxxxxxxxxxxxx No.xx xxxxxxx - xxxxxxxxx xxxxxx Telp : xxxx-xxxxxx Fax : xxxx-xxxxx',
            0, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Cell(19, 0.5,
            'homepage : www.krakatausteel.com  email : sacm@krakatausteel.com', 0, 0, 'C');
        /* Fungsi Line untuk membuat garis */
        $this->pdf->Line(1, 3.5, 20, 3.5);
        $this->pdf->Line(1, 3.55, 20, 3.55);
        /* -------------- Header Halaman selesai ------------------------------------------------*/
        $this->pdf->Ln(1);
        $this->pdf->SetFont('Times', 'B', 12);
        $this->pdf->Cell(19, 1, 'LAPORAN DATA KARYAWAN', 0, 0, 'C');
        /* setting header table */
        $this->pdf->Ln(1);
        $this->pdf->SetFont('Times', 'B', 12);
        $this->pdf->Cell(6, 1, 'NIK', 1, 'LR', 'L');
        $this->pdf->Cell(13, 1, 'NAMA KARYAWAN', 1, 'LR', 'L');
        
        /* generate hasil query disini */
        foreach ($karyawan->result() as $data) {
            $this->pdf->Ln();
            $this->pdf->SetFont('Times', '', 12);
            $this->pdf->Cell(6, 0.7, $data->karyawan_nik, 1, 'LR', 'L');
            $this->pdf->Cell(13, 0.7, $data->karyawan_nama, 1, 'LR', 'L');
        }
        /* setting posisi footer 3 cm dari bawah */
        $this->pdf->SetY(-3);
        /* setting font untuk footer */
        $this->pdf->SetFont('Times', '', 10);
        /* setting cell untuk waktu pencetakan */
        $this->pdf->Cell(9.5, 0.5, 'Printed on : ' . date('d/m/Y H:i') .
            ' | Created by : Heruno Utomo', 0, 'LR', 'L');
        /* setting cell untuk page number */
        $this->pdf->Cell(9.5, 0.5, 'Page ' . $this->pdf->PageNo() . '/{nb}', 0, 0, 'R');
        /* generate pdf jika semua konstruktor, data yang akan ditampilkan, dll sudah selesai */
        $this->pdf->Output(); //"data_karyawan.pdf","I"
    }
}

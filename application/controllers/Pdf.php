<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {

    private $temp_path;
    private $gs;
    private $soffice;

    public function __construct()
    {
        parent::__construct();

        $this->temp_path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $this->gs = '"C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe"';
        $this->soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';

        // Disable cache biar backend ringan
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Expires: 0");

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);
    }
/* =======================================================
   VIEW LOADERS
======================================================= */

public function compress()
{
    $data['title'] = 'Compress PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/compress');
    $this->load->view('layout/footer');
}

public function merge()
{
    $data['title'] = 'Merge PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/merge');
    $this->load->view('layout/footer');
}

public function split()
{
    $data['title'] = 'Split PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/split');
    $this->load->view('layout/footer');
}

public function rotate()
{
    $data['title'] = 'Rotate PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/rotate');
    $this->load->view('layout/footer');
}

public function remove_page()
{
    $data['title'] = 'Remove Page PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/remove_page');
    $this->load->view('layout/footer');
}

public function image_to_pdf()
{
    $data['title'] = 'Image to PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/image_to_pdf');
    $this->load->view('layout/footer');
}

public function pdf_to_word()
{
    $data['title'] = 'PDF to Word';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/pdf_to_word');
    $this->load->view('layout/footer');
}

public function word_to_pdf()
{
    $data['title'] = 'Word to PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/word_to_pdf');
    $this->load->view('layout/footer');
}

    /* =======================================================
       HELPER
    ======================================================= */

    private function upload_file($field, $types)
    {
        if (empty($_FILES[$field]['name'])) {
            show_error('File tidak ditemukan');
        }

        $config['upload_path']   = $this->temp_path;
        $config['allowed_types'] = $types;
        $config['max_size']      = 204800; // 200MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            show_error($this->upload->display_errors());
        }

        return $this->upload->data()['full_path'];
    }

    private function force_download($file, $name)
    {
        if (!file_exists($file)) {
            show_error('File tidak ditemukan');
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Content-Length: '.filesize($file));

        readfile($file);
        @unlink($file);
        exit;
    }

    /* =======================================================
       COMPRESS
    ======================================================= */

    public function process_compress()
    {
        $input  = $this->upload_file('pdf', 'pdf');
        $output = $this->temp_path.'compress_'.uniqid().'.pdf';

        $cmd = $this->gs
            ." -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 "
            ."-dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH "
            ."-sOutputFile=".escapeshellarg($output)." "
            .escapeshellarg($input);

        exec($cmd, $o, $r);

        @unlink($input);

        if ($r !== 0 || !file_exists($output)) {
            show_error('Gagal compress PDF');
        }

        $this->force_download($output, 'compressed.pdf');
    }

    /* =======================================================
       MERGE
    ======================================================= */

    public function process_merge()
    {
        if (empty($_FILES['pdf']['name'][0])) {
            show_error('Minimal 2 file');
        }

        $files = $_FILES['pdf'];
        $pdfs  = [];

        for ($i=0;$i<count($files['name']);$i++) {

            if ($files['type'][$i] !== 'application/pdf') continue;

            $tmp = $this->temp_path.uniqid('merge_').'.pdf';
            move_uploaded_file($files['tmp_name'][$i], $tmp);
            $pdfs[] = escapeshellarg($tmp);
        }

        if (count($pdfs) < 2) show_error('Minimal 2 file valid');

        $output = $this->temp_path.'merged_'.uniqid().'.pdf';

        $cmd = $this->gs
            .' -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite '
            .' -sOutputFile='.escapeshellarg($output).' '
            .implode(' ', $pdfs);

        exec($cmd, $o, $r);

        foreach ($pdfs as $f) @unlink(trim($f,"'"));

        if ($r !== 0 || !file_exists($output)) {
            show_error('Gagal merge');
        }

        $this->force_download($output, 'merged.pdf');
    }

    /* =======================================================
       ROTATE
    ======================================================= */

    public function process_rotate()
    {
        $rotate = (int)$this->input->post('rotate');

        $map = [90=>'east',180=>'south',270=>'west'];
        if (!isset($map[$rotate])) show_error('Rotasi tidak valid');

        $input  = $this->upload_file('pdf', 'pdf');
        $output = $this->temp_path.'rotate_'.uniqid().'.pdf';

        $cmd = 'pdftk '
            .escapeshellarg($input)
            .' cat 1-end '
            .$map[$rotate]
            .' output '
            .escapeshellarg($output);

        exec($cmd, $o, $r);

        @unlink($input);

        if ($r !== 0 || !file_exists($output)) {
            show_error('Gagal rotate');
        }

        $this->force_download($output, 'rotated.pdf');
    }

    /* =======================================================
       IMAGE → PDF
    ======================================================= */

    public function process_image_to_pdf()
    {
        require_once APPPATH.'third_party/fpdf/fpdf.php';

        if (empty($_FILES['image']['name'][0])) {
            show_error('Tidak ada gambar');
        }

        $pdf = new FPDF();

        foreach ($_FILES['image']['name'] as $i=>$name) {

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext,['jpg','jpeg','png'])) continue;

            $tmp = $this->temp_path.uniqid('img_').'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'][$i], $tmp);

            list($w,$h)=getimagesize($tmp);

            $pdf->AddPage();
            $ratio=min(190/$w,270/$h);
            $pdf->Image($tmp,10,10,$w*$ratio,$h*$ratio);

            @unlink($tmp);
        }

        $output=$this->temp_path.'image_'.uniqid().'.pdf';
        $pdf->Output('F',$output);

        $this->force_download($output,'image.pdf');
    }

    /* =======================================================
       PDF → WORD
    ======================================================= */

    public function process_pdf_to_word()
    {
        $input = $this->upload_file('pdf','pdf');

        $cmd = $this->soffice
            .' --headless --convert-to docx '
            .escapeshellarg($input)
            .' --outdir '
            .escapeshellarg($this->temp_path);

        exec($cmd,$o,$r);

        $output=$this->temp_path.pathinfo($input,PATHINFO_FILENAME).'.docx';

        @unlink($input);

        if ($r!==0||!file_exists($output)) show_error('Gagal convert');

        $this->force_download($output,'converted.docx');
    }

    /* =======================================================
       WORD → PDF
    ======================================================= */

    public function process_word_to_pdf()
    {
        $input=$this->upload_file('doc','doc|docx');

        $cmd=$this->soffice
            .' --headless --convert-to pdf '
            .escapeshellarg($input)
            .' --outdir '
            .escapeshellarg($this->temp_path);

        exec($cmd,$o,$r);

        $output=$this->temp_path.pathinfo($input,PATHINFO_FILENAME).'.pdf';

        @unlink($input);

        if($r!==0||!file_exists($output)) show_error('Gagal convert');

        $this->force_download($output,'converted.pdf');
    }

}

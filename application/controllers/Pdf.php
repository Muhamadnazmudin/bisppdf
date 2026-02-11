<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {

    public function compress()
    {
        $data['title'] = 'Compress PDF';
        $this->load->view('layout/header', $data);
        $this->load->view('pdf/compress');
        $this->load->view('layout/footer');
    }

    public function process_compress()
    {
        if (empty($_FILES['pdf']['name'])) {
            show_error('File tidak ditemukan');
        }

        $config['upload_path']   = FCPATH.'storage/uploads/';
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 20480; // 20MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('pdf')) {
            show_error($this->upload->display_errors());
        }

        $file = $this->upload->data();

        $input  = $file['full_path'];
        $output = FCPATH.'storage/outputs/compressed_'.$file['file_name'];

        // PATH GHOSTSCRIPT
        $gs = '"C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe"';

        $cmd = $gs." -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 ".
               "-dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH ".
               "-sOutputFile=\"{$output}\" \"{$input}\"";

        exec($cmd, $out, $ret);

        if ($ret !== 0 || !file_exists($output)) {
            show_error('Gagal compress PDF');
        }

        // AUTO DOWNLOAD
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="compressed.pdf"');
        readfile($output);

        // CLEANUP
        @unlink($input);
        @unlink($output);
    }
    public function merge()
{
    $data['title'] = 'Merge PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/merge');
    $this->load->view('layout/footer');
}

public function process_merge()
{
    if (empty($_FILES['pdf']['name'][0])) {
        show_error('Minimal upload 2 file PDF');
    }

    $upload_path = FCPATH.'storage/uploads/';
    $output_path = FCPATH.'storage/outputs/';
    $files = $_FILES['pdf'];
    $pdfs  = [];

    for ($i = 0; $i < count($files['name']); $i++) {

        // validasi MIME sederhana
        if ($files['type'][$i] !== 'application/pdf') {
            continue;
        }

        $new_name = uniqid('pdf_').'.pdf';
        $target   = $upload_path.$new_name;

        if (move_uploaded_file($files['tmp_name'][$i], $target)) {
            $pdfs[] = escapeshellarg($target);
        }
    }

    if (count($pdfs) < 2) {
        show_error('Minimal 2 file PDF valid');
    }

    $output = $output_path.'merged_'.time().'.pdf';

    // PATH GHOSTSCRIPT (pakai yg sama)
    $gs = '"C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe"';

    $cmd = $gs.' -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite '.
           '-sOutputFile='.escapeshellarg($output).' '.
           implode(' ', $pdfs);

    exec($cmd, $out, $ret);

    // cleanup upload
    foreach ($pdfs as $f) {
        @unlink(trim($f, "'"));
    }

    if ($ret !== 0 || !file_exists($output)) {
        show_error('Gagal merge PDF');
    }

    // auto download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="merged.pdf"');
    readfile($output);

    @unlink($output);
    exit;
}
public function split()
{
    $data['title'] = 'Split PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/split');
    $this->load->view('layout/footer');
}

public function process_split()
{
    if (empty($_FILES['pdf']['name'])) {
        show_error('File PDF tidak ditemukan');
    }

    $range = trim($this->input->post('range'));
    if ($range === '') {
        show_error('Range halaman wajib diisi');
    }

    // upload config
    $config['upload_path']   = FCPATH.'storage/uploads/';
    $config['allowed_types'] = 'pdf';
    $config['max_size']      = 20480;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('pdf')) {
        show_error($this->upload->display_errors());
    }

    $file   = $this->upload->data();
    $input  = $file['full_path'];
    $output = FCPATH.'storage/outputs/split_'.time().'.pdf';

    // PATH GHOSTSCRIPT
    $gs = '"C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe"';

    // contoh range: 1-3, 5
    if (strpos($range, '-') !== false) {
        list($start, $end) = explode('-', $range);
        $cmd = $gs." -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dQUIET ".
               "-dFirstPage={$start} -dLastPage={$end} ".
               "-sOutputFile=\"{$output}\" \"{$input}\"";
    } else {
        $cmd = $gs." -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dQUIET ".
               "-dFirstPage={$range} -dLastPage={$range} ".
               "-sOutputFile=\"{$output}\" \"{$input}\"";
    }

    exec($cmd, $out, $ret);

    if ($ret !== 0 || !file_exists($output)) {
        show_error('Gagal split PDF');
    }

    // auto download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="split.pdf"');
    readfile($output);

    // cleanup
    @unlink($input);
    @unlink($output);
    exit;
}
public function rotate()
{
    $data['title'] = 'Rotate PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/rotate');
    $this->load->view('layout/footer');
}

public function process_rotate()
{
    if (empty($_FILES['pdf']['name'])) {
        show_error('File PDF tidak ditemukan');
    }

    $rotate = (int) $this->input->post('rotate');

    // mapping rotasi pdftk
    $map = [
        90  => 'east',
        180 => 'south',
        270 => 'west'
    ];

    if (!isset($map[$rotate])) {
        show_error('Rotasi tidak valid');
    }

    // upload config
    $config['upload_path']   = FCPATH.'storage/uploads/';
    $config['allowed_types'] = 'pdf';
    $config['max_size']      = 20480;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('pdf')) {
        show_error($this->upload->display_errors());
    }

    $file   = $this->upload->data();
    $input  = $file['full_path'];
    $output = FCPATH.'storage/outputs/rotate_'.time().'.pdf';

    // pdftk command (SYNTAX BENAR)
    $cmd = 'pdftk '
         . escapeshellarg($input)
         . ' cat 1-end '
         . $map[$rotate]
         . ' output '
         . escapeshellarg($output);

    exec($cmd, $out, $ret);

    if ($ret !== 0 || !file_exists($output)) {
        show_error('Gagal rotate PDF');
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="rotated.pdf"');
    readfile($output);

    @unlink($input);
    @unlink($output);
    exit;
}
public function remove_page()
{
    $data['title'] = 'Remove Page PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/remove_page');
    $this->load->view('layout/footer');
}

public function process_remove_page()
{
    if (empty($_FILES['pdf']['name'])) {
        show_error('File PDF tidak ditemukan');
    }

    $remove = trim($this->input->post('pages'));
    if ($remove === '') {
        show_error('Halaman yang dihapus wajib diisi');
    }

    // upload config
    $config['upload_path']   = FCPATH.'storage/uploads/';
    $config['allowed_types'] = 'pdf';
    $config['max_size']      = 20480;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('pdf')) {
        show_error($this->upload->display_errors());
    }

    $file   = $this->upload->data();
    $input  = $file['full_path'];
    $output = FCPATH.'storage/outputs/remove_'.time().'.pdf';

    /**
     * pdftk logic:
     * ambil semua halaman
     * kecuali yang user sebutkan
     */
    $cmd = 'pdftk '
         . escapeshellarg($input)
         . ' cat 1-end~'
         . escapeshellarg($remove)
         . ' output '
         . escapeshellarg($output);

    exec($cmd, $out, $ret);

    if ($ret !== 0 || !file_exists($output)) {
        show_error('Gagal remove page PDF');
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="removed.pdf"');
    readfile($output);

    @unlink($input);
    @unlink($output);
    exit;
}
public function image_to_pdf()
{
    $data['title'] = 'Image to PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/image_to_pdf');
    $this->load->view('layout/footer');
}

public function process_image_to_pdf()
{
    if (empty($_FILES['image']['name'][0])) {
        show_error('Tidak ada gambar diupload');
    }

    $upload_path = FCPATH.'storage/uploads/';
    $output      = FCPATH.'storage/outputs/image_'.time().'.pdf';

    require_once APPPATH.'third_party/fpdf/fpdf.php';
    $pdf = new FPDF();

    foreach ($_FILES['image']['name'] as $i => $name) {

        $tmp  = $_FILES['image']['tmp_name'][$i];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        // validasi extension
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            continue;
        }

        // simpan file sementara DENGAN extension
        $temp_image = $upload_path.uniqid('img_').'.'.$ext;
        move_uploaded_file($tmp, $temp_image);

        list($w, $h) = getimagesize($temp_image);

        // hitung ukuran halaman
        $pdf->AddPage();
        $page_w = $pdf->GetPageWidth() - 20;
        $page_h = $pdf->GetPageHeight() - 20;

        // resize proporsional
        $ratio = min($page_w / $w, $page_h / $h);
        $new_w = $w * $ratio;
        $new_h = $h * $ratio;

        $x = ($pdf->GetPageWidth() - $new_w) / 2;
        $y = ($pdf->GetPageHeight() - $new_h) / 2;

        $pdf->Image($temp_image, $x, $y, $new_w, $new_h);

        // hapus file temp
        @unlink($temp_image);
    }

    $pdf->Output('F', $output);

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="image.pdf"');
    readfile($output);

    @unlink($output);
    exit;
}
public function pdf_to_word()
{
    $data['title'] = 'PDF to Word';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/pdf_to_word');
    $this->load->view('layout/footer');
}

public function process_pdf_to_word()
{
    if (empty($_FILES['pdf']['name'])) {
        show_error('File PDF tidak ditemukan');
    }

    // upload config
    $config['upload_path']   = FCPATH.'storage/uploads/';
    $config['allowed_types'] = 'pdf';
    $config['max_size']      = 20480;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('pdf')) {
        show_error($this->upload->display_errors());
    }

    $file   = $this->upload->data();
    $input  = $file['full_path'];
    $outdir = FCPATH.'storage/outputs/';

    // PATH LIBREOFFICE (FULL PATH)
    $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';

    // convert PDF → DOCX
    $cmd = $soffice
         . ' --headless --convert-to docx '
         . escapeshellarg($input)
         . ' --outdir '
         . escapeshellarg($outdir);

    exec($cmd, $out, $ret);

    // hasil docx (LibreOffice pakai nama sama)
    $output = $outdir.pathinfo($file['file_name'], PATHINFO_FILENAME).'.docx';

    if ($ret !== 0 || !file_exists($output)) {
        show_error('Gagal convert PDF ke Word');
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="converted.docx"');
    readfile($output);

    @unlink($input);
    @unlink($output);
    exit;
}
public function word_to_pdf()
{
    $data['title'] = 'Word to PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/word_to_pdf');
    $this->load->view('layout/footer');
}

public function process_word_to_pdf()
{
    if (empty($_FILES['doc']['name'])) {
        show_error('File Word tidak ditemukan');
    }

    $config['upload_path']   = FCPATH.'storage/uploads/';
    $config['allowed_types'] = 'doc|docx';
    $config['max_size']      = 20480;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('doc')) {
        show_error($this->upload->display_errors());
    }

    $file   = $this->upload->data();
    $input  = $file['full_path'];
    $outdir = FCPATH.'storage/outputs/';

    // LibreOffice (FULL PATH)
    $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';

    $cmd = $soffice
         . ' --headless --convert-to pdf '
         . escapeshellarg($input)
         . ' --outdir '
         . escapeshellarg($outdir);

    exec($cmd, $out, $ret);

    $output = $outdir.pathinfo($file['file_name'], PATHINFO_FILENAME).'.pdf';

    if ($ret !== 0 || !file_exists($output)) {
        show_error('Gagal convert Word ke PDF');
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="converted.pdf"');
    readfile($output);

    @unlink($input);
    @unlink($output);
    exit;
}

}

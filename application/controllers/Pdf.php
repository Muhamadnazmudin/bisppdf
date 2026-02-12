<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {

    private $temp_path;
    private $gs;
    private $soffice;

    public function __construct()
    {
        parent::__construct();

        // $this->temp_path = FCPATH . 'temp' . DIRECTORY_SEPARATOR;
        $this->temp_path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;


        $this->gs = '"C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe"';
        $this->soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';
        $this->pdftk = '"C:\Program Files (x86)\PDFtk\bin\pdftk.exe"';

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
public function delete()
{
    $data['title'] = 'Delete PDF';
    $this->load->view('layout/header', $data);
    $this->load->view('pdf/delete');
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

    // 🔥 pastikan lock dilepas
    $this->release_lock();

    exit;
}
public function process_split()
{
    $input = $this->upload_file('pdf', 'pdf');

    $originalName = pathinfo($_FILES['pdf']['name'], PATHINFO_FILENAME);
    $originalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

    $mode = $this->input->post('mode');

    // =====================================================
    // CUSTOM RANGE
    // =====================================================
    if ($mode == 'custom') {

        $range = $this->input->post('range');

        if (empty($range)) {
            @unlink($input);
            show_error('Range halaman tidak boleh kosong');
        }

        $output = $this->temp_path . 'split_' . uniqid() . '.pdf';

        $cmd = $this->pdftk
            . ' ' . escapeshellarg($input)
            . ' cat ' . escapeshellarg($range)
            . ' output '
            . escapeshellarg($output);

        exec($cmd, $o, $r);
        @unlink($input);

        if ($r !== 0 || !file_exists($output)) {
            show_error('Gagal split PDF');
        }

        $this->force_download($output, $originalName.'_split.pdf');
    }

    // =====================================================
    // FIXED RANGE
    // =====================================================
    else {

        $fixed = (int) $this->input->post('fixed_range');

        if ($fixed < 1) {
            @unlink($input);
            show_error('Nilai fixed range tidak valid');
        }

        // ambil total halaman
        $infoCmd = $this->pdftk . ' ' . escapeshellarg($input) . ' dump_data';
        exec($infoCmd, $infoOutput);

        $totalPages = 0;
        foreach ($infoOutput as $line) {
            if (strpos($line, 'NumberOfPages') !== false) {
                $parts = explode(':', $line);
                $totalPages = (int) trim($parts[1]);
                break;
            }
        }

        if ($totalPages <= 0) {
            @unlink($input);
            show_error('Gagal membaca jumlah halaman');
        }

        // folder unik
        $processFolder = $this->temp_path . 'split_' . uniqid() . DIRECTORY_SEPARATOR;
        mkdir($processFolder, 0777, true);

        $start = 1;
        $index = 1;

        while ($start <= $totalPages) {

            $end = min($start + $fixed - 1, $totalPages);

            $range = $start . '-' . $end;

            $outputFile = $processFolder . 'part_' . $index . '.pdf';

            $cmd = $this->pdftk
                . ' ' . escapeshellarg($input)
                . ' cat ' . $range
                . ' output '
                . escapeshellarg($outputFile);

            exec($cmd);

            $start += $fixed;
            $index++;
        }

        @unlink($input);

        // buat ZIP
        $zipPath = $this->temp_path . 'split_' . uniqid() . '.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);

        foreach (glob($processFolder . '*.pdf') as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        // bersihkan
        foreach (glob($processFolder . '*.pdf') as $file) {
            @unlink($file);
        }
        rmdir($processFolder);

        // bersihkan buffer
        if (ob_get_level()) ob_end_clean();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.$originalName.'_split.zip"');
        header('Content-Length: '.filesize($zipPath));

        readfile($zipPath);
        @unlink($zipPath);
        exit;
    }
}

    /* =======================================================
       COMPRESS
    ======================================================= */

   public function process_compress()
{
    $this->check_rate_limit(15);
    $this->acquire_lock();

    $input  = $this->upload_file('pdf', 'pdf');

    // 🔥 ambil nama asli
    $originalName = pathinfo($_FILES['pdf']['name'], PATHINFO_FILENAME);

    // bersihkan karakter aneh
    $originalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

    $output = $this->temp_path.'compress_'.uniqid().'.pdf';

    $level = $this->input->post('level');

switch ($level) {
    case 'low':
        $pdfSetting = '/prepress'; // kualitas tinggi
        break;

    case 'high':
        $pdfSetting = '/screen'; // kompres maksimal
        break;

    default:
        $pdfSetting = '/ebook'; // medium
        break;
}

$cmd = $this->gs
    ." -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 "
    ."-dPDFSETTINGS={$pdfSetting} -dNOPAUSE -dQUIET -dBATCH "
    ."-sOutputFile=".escapeshellarg($output)." "
    .escapeshellarg($input);


    exec($cmd, $o, $r);

    @unlink($input);

    if ($r !== 0 || !file_exists($output)) {
        $this->release_lock();
        show_error('Gagal compress PDF');
    }

    // 🔥 nama baru
   $downloadName = $this->generate_download_name($_FILES['pdf']['name'], 'kompres');

$this->force_download($output, $downloadName);

}


    /* =======================================================
       MERGE
    ======================================================= */

    public function process_merge()
{
    if (empty($_FILES['pdf']['name'][0])) {
        echo json_encode(['success'=>false,'message'=>'Minimal 2 file']);
        return;
    }

    $files = $_FILES['pdf'];
    $pdfs  = [];

    for ($i=0;$i<count($files['name']);$i++) {

        if ($files['type'][$i] !== 'application/pdf') continue;

        $tmp = $this->temp_path.uniqid('merge_').'.pdf';
        move_uploaded_file($files['tmp_name'][$i], $tmp);
        $pdfs[] = escapeshellarg($tmp);
    }

    if (count($pdfs) < 2) {
        echo json_encode(['success'=>false,'message'=>'Minimal 2 file valid']);
        return;
    }

    $outputName = 'merged_'.uniqid().'.pdf';
    $output = $this->temp_path.$outputName;

    $cmd = $this->gs
        .' -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite '
        .' -sOutputFile='.escapeshellarg($output).' '
        .implode(' ', $pdfs);

    exec($cmd, $o, $r);

    foreach ($pdfs as $f) @unlink(trim($f,"'"));

    if ($r !== 0 || !file_exists($output)) {
        echo json_encode(['success'=>false,'message'=>'Gagal merge']);
        return;
    }

    echo json_encode([
        'success'=>true,
        'file'=>$outputName
    ]);
}
public function download_merge($file)
{
    $path = $this->temp_path.$file;

    if (!file_exists($path)) {
        show_404();
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="merged.pdf"');
    header('Content-Length: '.filesize($path));

    readfile($path);

    unlink($path); // 🔥 AUTO DELETE SETELAH DOWNLOAD
    exit;
}

    /* =======================================================
       ROTATE
    ======================================================= */

    public function process_rotate()
{
    $input  = $this->upload_file('pdf', 'pdf');
    $output = $this->temp_path . 'rotate_' . uniqid() . '.pdf';

    $rotationsJson = $this->input->post('rotations');

    if (empty($rotationsJson)) {
        show_error('Tidak ada data rotasi');
    }

    $rotations = json_decode($rotationsJson, true);

    if (!is_array($rotations)) {
        show_error('Format rotasi tidak valid');
    }

    $rotationMap = [
        0   => '',
        90  => 'right',
        180 => 'down',
        270 => 'left'
    ];

    $pagesCommand = '';

    foreach ($rotations as $page => $angle) {

        $angle = (int)$angle;

        if (!isset($rotationMap[$angle])) {
            @unlink($input);
            show_error('Rotasi tidak valid');
        }

        if ($rotationMap[$angle] === '') {
            $pagesCommand .= $page . ' ';
        } else {
            $pagesCommand .= $page . $rotationMap[$angle] . ' ';
        }
    }

    $cmd = $this->pdftk
        . ' ' . escapeshellarg($input)
        . ' cat ' . trim($pagesCommand)
        . ' output '
        . escapeshellarg($output);

    exec($cmd, $log);

    @unlink($input);

    if (!file_exists($output)) {
        echo "<pre>$cmd\n\n";
        print_r($log);
        echo "</pre>";
        show_error('Gagal rotate PDF');
    }

    $downloadName = $this->generate_download_name($_FILES['pdf']['name'], 'rotate');

    $this->force_download($output, $downloadName);
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
private function check_rate_limit($seconds = 15)
{
    $ip = $this->input->ip_address();
    $file = sys_get_temp_dir() . '/pdf_rate_' . md5($ip) . '.txt';

    if (file_exists($file)) {

        $last = (int) file_get_contents($file);

        if ((time() - $last) < $seconds) {
            show_error('Tunggu ' . ($seconds - (time() - $last)) . ' detik sebelum upload lagi.');
        }
    }

    file_put_contents($file, time());
}

private function acquire_lock()
{
    $lock = sys_get_temp_dir() . '/pdf_processing.lock';

    if (file_exists($lock)) {

        $created = filemtime($lock);

        // auto expire 2 menit
        if ((time() - $created) < 120) {
            show_error('Server sedang memproses file lain.');
        }

        unlink($lock);
    }

    file_put_contents($lock, time());
}

private function release_lock()
{
    $lock = sys_get_temp_dir() . '/pdf_processing.lock';
    if (file_exists($lock)) unlink($lock);
}
private function generate_download_name($originalName, $action, $extension = 'pdf')
{
    // ambil nama tanpa extension
    $name = pathinfo($originalName, PATHINFO_FILENAME);

    // bersihkan karakter aneh
    $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);

    return $name . '_' . $action . '.' . $extension;
}
public function delete_file($name)
{
    $file = FCPATH.'downloads/'.$name;
    if (file_exists($file)) unlink($file);
}
public function process_delete()
{
    $input = $this->upload_file('pdf_file', 'pdf');

    $pages = $this->input->post('pages'); // contoh: 2,5,7

    if (empty($pages)) {
        show_error('Halaman tidak boleh kosong');
    }

    // ambil total halaman
    $infoCmd = 'pdftk ' . escapeshellarg($input) . ' dump_data';
    exec($infoCmd, $infoOutput);

    $totalPages = 0;
    foreach ($infoOutput as $line) {
        if (strpos($line, 'NumberOfPages') !== false) {
            $parts = explode(':', $line);
            $totalPages = (int) trim($parts[1]);
            break;
        }
    }

    if ($totalPages <= 0) {
        @unlink($input);
        show_error('Gagal membaca jumlah halaman');
    }

    // halaman yang ingin dihapus
    $deletePages = array_map('intval', explode(',', $pages));

    // buat daftar halaman yang disimpan
    $keepPages = [];

    for ($i = 1; $i <= $totalPages; $i++) {
        if (!in_array($i, $deletePages)) {
            $keepPages[] = $i;
        }
    }

    if (empty($keepPages)) {
        @unlink($input);
        show_error('Tidak boleh menghapus semua halaman');
    }

    $output = $this->temp_path . 'delete_' . uniqid() . '.pdf';

    $cmd = 'pdftk '
        . escapeshellarg($input)
        . ' cat '
        . implode(' ', $keepPages)
        . ' output '
        . escapeshellarg($output);

    exec($cmd, $o, $r);

    @unlink($input);

    if ($r !== 0 || !file_exists($output)) {
        show_error('Gagal menghapus halaman');
    }

    $downloadName = $this->generate_download_name($_FILES['pdf_file']['name'], 'delete');

    $this->force_download($output, $downloadName);
}

}

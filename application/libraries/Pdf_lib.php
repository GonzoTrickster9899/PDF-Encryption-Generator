<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include TCPDF library
require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');

class Pdf_lib {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Create protected PDF with encryption
     */
    public function create_protected_pdf($data) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('PDF Generator System');
        $pdf->SetTitle($data['title']);
        $pdf->SetSubject('Protected PDF Document');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        // Set font
        $pdf->SetFont('helvetica', '', 11);

        // Add a page
        $pdf->AddPage();

        // Add title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, $data['title'], 0, 1, 'C');
        $pdf->Ln(5);

        // Add content based on type
        $pdf->SetFont('helvetica', '', 11);
        
        if ($data['content_type'] == 'html') {
            // Write HTML content
            $pdf->writeHTML($data['content'], true, false, true, false, '');
        } else {
            // Write plain text content
            $pdf->MultiCell(0, 5, $data['content'], 0, 'L', false, 1);
        }

        // Set permissions
        $permissions = $this->get_tcpdf_permissions($data['permissions']);

        // Set protection with passwords
        $pdf->SetProtection(
            $permissions,
            $data['user_password'],
            $data['owner_password'],
            0,
            null
        );

        // Output PDF to file
        $pdf->Output($data['filepath'], 'F');

        return true;
    }

    /**
     * Convert permission array to TCPDF permission constants
     */
    private function get_tcpdf_permissions($permissions) {
        $tcpdf_permissions = array();

        if (in_array('print', $permissions)) {
            $tcpdf_permissions[] = 'print';
        }
        if (in_array('modify', $permissions)) {
            $tcpdf_permissions[] = 'modify';
        }
        if (in_array('copy', $permissions)) {
            $tcpdf_permissions[] = 'copy';
        }

        // If no permissions set, deny all
        if (empty($tcpdf_permissions)) {
            $tcpdf_permissions = array();
        }

        return $tcpdf_permissions;
    }

    /**
     * Create PDF from existing PDF using FPDI
     */
    public function protect_existing_pdf($input_file, $output_file, $user_password, $owner_password, $permissions) {
        // Check if FPDI is available
        if (!file_exists(APPPATH . 'third_party/fpdi/src/autoload.php')) {
            throw new Exception('FPDI library not found');
        }

        require_once(APPPATH . 'third_party/fpdi/src/autoload.php');

        // Create new FPDI instance
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

        // Get page count
        $pageCount = $pdf->setSourceFile($input_file);

        // Import all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            // Add page with same orientation as original
            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }

            // Use the imported page
            $pdf->useTemplate($templateId);
        }

        // Set protection
        $tcpdf_permissions = $this->get_tcpdf_permissions($permissions);
        $pdf->SetProtection($tcpdf_permissions, $user_password, $owner_password, 0, null);

        // Output to file
        $pdf->Output($output_file, 'F');

        return true;
    }

    /**
     * Create PDF with table content
     */
    public function create_table_pdf($data) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('PDF Generator System');
        $pdf->SetTitle($data['title']);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();

        // Title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, $data['title'], 0, 1, 'C');
        $pdf->Ln(5);

        // Create HTML table from data
        $html = '<table border="1" cellpadding="5">';
        
        if (isset($data['table_headers'])) {
            $html .= '<thead><tr>';
            foreach ($data['table_headers'] as $header) {
                $html .= '<th><b>' . htmlspecialchars($header) . '</b></th>';
            }
            $html .= '</tr></thead>';
        }

        if (isset($data['table_rows'])) {
            $html .= '<tbody>';
            foreach ($data['table_rows'] as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Set protection
        $permissions = $this->get_tcpdf_permissions($data['permissions']);
        $pdf->SetProtection($permissions, $data['user_password'], $data['owner_password'], 0, null);

        $pdf->Output($data['filepath'], 'F');

        return true;
    }
}
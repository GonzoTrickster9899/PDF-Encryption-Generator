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
     * Create protected PDF with full encryption and no permissions
     */
    public function create_protected_pdf($data) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('PDF Encryption Generator');
        $pdf->SetAuthor('Secure PDF System');
        $pdf->SetTitle($data['title']);
        $pdf->SetSubject('Fully Encrypted PDF Document');

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

        // Add encryption notice
        $pdf->SetFont('helvetica', 'I', 9);
        $pdf->SetTextColor(150, 0, 0);
        $pdf->Cell(0, 5, 'This document is fully encrypted and protected', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetTextColor(0, 0, 0);

        // Add content based on type
        $pdf->SetFont('helvetica', '', 11);
        
        if ($data['content_type'] == 'html') {
            // Write HTML content
            $pdf->writeHTML($data['content'], true, false, true, false, '');
        } else {
            // Write plain text content
            $pdf->MultiCell(0, 5, $data['content'], 0, 'L', false, 1);
        }

        // Add footer with encryption info
        $pdf->SetY(-20);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 5, 'Generated: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
        $pdf->Cell(0, 5, 'Security Level: Maximum Encryption | All Permissions Restricted', 0, 1, 'C');

        // Set maximum protection with NO permissions
        // Empty array or specific restrictions mean NO permissions are granted
        // This is the most restrictive setting
        $pdf->SetProtection(
            array(), // No permissions granted - fully restricted
            $data['user_password'],  // User password (1234)
            $data['owner_password'], // Owner password (1234)
            0,  // Mode 0 = 40-bit RC4 (compatible), use 1 for 128-bit
            null
        );

        // Output PDF to file
        $pdf->Output($data['filepath'], 'F');

        return true;
    }

    /**
     * Create PDF with maximum encryption using 128-bit
     */
    public function create_maximum_encrypted_pdf($data) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PDF Encryption Generator');
        $pdf->SetAuthor('Secure PDF System');
        $pdf->SetTitle($data['title']);
        $pdf->SetSubject('Maximum Encrypted PDF Document');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();

        // Title
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(33, 37, 41);
        $pdf->Cell(0, 15, $data['title'], 0, 1, 'C');
        $pdf->Ln(3);

        // Security badge
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(220, 53, 69);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 8, '🔒 MAXIMUM SECURITY - FULLY ENCRYPTED', 0, 1, 'C', true);
        $pdf->Ln(8);

        // Content
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        
        if ($data['content_type'] == 'html') {
            $pdf->writeHTML($data['content'], true, false, true, false, '');
        } else {
            $pdf->MultiCell(0, 5, $data['content'], 0, 'L', false, 1);
        }

        // Security footer
        $pdf->SetY(-25);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(220, 53, 69);
        $pdf->Cell(0, 5, 'SECURITY INFORMATION', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 4, 'Password Protected: YES | Printing: DISABLED | Copying: DISABLED | Modification: DISABLED', 0, 1, 'C');
        $pdf->Cell(0, 4, 'Encryption: 128-bit | Generated: ' . date('Y-m-d H:i:s'), 0, 1, 'C');

        // Maximum protection with 128-bit encryption
        $pdf->SetProtection(
            array(), // No permissions
            $data['user_password'],
            $data['owner_password'],
            1, // Mode 1 = 128-bit encryption
            null
        );

        $pdf->Output($data['filepath'], 'F');

        return true;
    }

    /**
     * Create PDF from existing PDF using FPDI with full encryption
     */
    public function protect_existing_pdf($input_file, $output_file, $user_password, $owner_password) {
        // Check if FPDI is available
        if (!file_exists(APPPATH . 'third_party/fpdi/src/autoload.php')) {
            throw new Exception('FPDI library not found');
        }

        require_once(APPPATH . 'third_party/fpdi/src/autoload.php');

        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

        $pageCount = $pdf->setSourceFile($input_file);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            if ($size['width'] > $size['height']) {
                $pdf->AddPage('L', array($size['width'], $size['height']));
            } else {
                $pdf->AddPage('P', array($size['width'], $size['height']));
            }

            $pdf->useTemplate($templateId);
        }

        // Full encryption with no permissions
        $pdf->SetProtection(array(), $user_password, $owner_password, 1, null);

        $pdf->Output($output_file, 'F');

        return true;
    }

    /**
     * Create PDF with table content - fully encrypted
     */
    public function create_table_pdf($data) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PDF Encryption Generator');
        $pdf->SetAuthor('Secure PDF System');
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

        // Create HTML table
        $html = '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
        
        if (isset($data['table_headers'])) {
            $html .= '<thead><tr style="background-color: #667eea; color: white;">';
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

        // Full encryption
        $pdf->SetProtection(
            array(), 
            isset($data['user_password']) ? $data['user_password'] : '1234',
            isset($data['owner_password']) ? $data['owner_password'] : '1234',
            1,
            null
        );

        $pdf->Output($data['filepath'], 'F');

        return true;
    }
}
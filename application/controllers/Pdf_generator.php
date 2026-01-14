<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_generator extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pdf_model');
        $this->load->library('Pdf_lib');
    }

    public function index() {
        $data['pdfs'] = $this->Pdf_model->get_all_pdfs();
        $this->load->view('pdf_form', $data);
    }

    public function generate() {
        // Validate input
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('content_type', 'Content Type', 'required');
        $this->form_validation->set_rules('content', 'Content', 'required');
        $this->form_validation->set_rules('user_password', 'User Password', 'required|min_length[4]');
        $this->form_validation->set_rules('owner_password', 'Owner Password', 'required|min_length[4]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('pdf_generator');
            return;
        }

        // Get form data
        $title = $this->input->post('title', TRUE);
        $content_type = $this->input->post('content_type', TRUE);
        $content = $this->input->post('content', TRUE);
        $user_password = $this->input->post('user_password', TRUE);
        $owner_password = $this->input->post('owner_password', TRUE);
        
        // Get permissions
        $permissions = array();
        if ($this->input->post('allow_print')) {
            $permissions[] = 'print';
        }
        if ($this->input->post('allow_copy')) {
            $permissions[] = 'copy';
        }
        if ($this->input->post('allow_modify')) {
            $permissions[] = 'modify';
        }

        // Generate unique filename
        $filename = 'pdf_' . time() . '_' . uniqid() . '.pdf';
        $filepath = APPPATH . 'data/files/' . $filename;

        // Create files directory if not exists
        if (!is_dir(APPPATH . 'data/files/')) {
            mkdir(APPPATH . 'data/files/', 0755, true);
        }

        // Generate PDF
        try {
            $pdf_data = array(
                'title' => $title,
                'content' => $content,
                'content_type' => $content_type,
                'user_password' => $user_password,
                'owner_password' => $owner_password,
                'permissions' => $permissions,
                'filepath' => $filepath
            );

            $this->pdf_lib->create_protected_pdf($pdf_data);

            // Save to JSON database
            $record = array(
                'id' => uniqid(),
                'title' => $title,
                'filename' => $filename,
                'content_type' => $content_type,
                'created_at' => date('Y-m-d H:i:s'),
                'file_size' => filesize($filepath)
            );

            $this->Pdf_model->save_pdf($record);

            $this->session->set_flashdata('success', 'PDF generated successfully!');
            redirect('pdf_generator');

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error generating PDF: ' . $e->getMessage());
            redirect('pdf_generator');
        }
    }

    public function download($id) {
        $pdf = $this->Pdf_model->get_pdf_by_id($id);
        
        if (!$pdf) {
            show_404();
            return;
        }

        $filepath = APPPATH . 'data/files/' . $pdf['filename'];

        if (!file_exists($filepath)) {
            $this->session->set_flashdata('error', 'File not found!');
            redirect('pdf_generator');
            return;
        }

        // Force download
        $this->load->helper('download');
        $data = file_get_contents($filepath);
        force_download($pdf['filename'], $data);
    }

    public function delete($id) {
        $pdf = $this->Pdf_model->get_pdf_by_id($id);
        
        if (!$pdf) {
            $this->session->set_flashdata('error', 'PDF not found!');
            redirect('pdf_generator');
            return;
        }

        // Delete file
        $filepath = APPPATH . 'data/files/' . $pdf['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        // Delete from database
        $this->Pdf_model->delete_pdf($id);

        $this->session->set_flashdata('success', 'PDF deleted successfully!');
        redirect('pdf_generator');
    }

    public function list_pdfs() {
        $data['pdfs'] = $this->Pdf_model->get_all_pdfs();
        $this->load->view('pdf_list', $data);
    }
}
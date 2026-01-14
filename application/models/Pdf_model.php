<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_model extends CI_Model {

    private $json_file;

    public function __construct() {
        parent::__construct();
        $this->json_file = APPPATH . 'data/pdfs.json';
        
        // Create data directory if not exists
        if (!is_dir(APPPATH . 'data/')) {
            mkdir(APPPATH . 'data/', 0755, true);
        }

        // Create JSON file if not exists
        if (!file_exists($this->json_file)) {
            file_put_contents($this->json_file, json_encode(array()));
        }
    }

    /**
     * Get all PDFs from JSON database
     */
    public function get_all_pdfs() {
        $json_data = file_get_contents($this->json_file);
        $pdfs = json_decode($json_data, true);
        
        if (!is_array($pdfs)) {
            return array();
        }

        // Sort by created_at descending
        usort($pdfs, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $pdfs;
    }

    /**
     * Get PDF by ID
     */
    public function get_pdf_by_id($id) {
        $pdfs = $this->get_all_pdfs();
        
        foreach ($pdfs as $pdf) {
            if ($pdf['id'] == $id) {
                return $pdf;
            }
        }
        
        return null;
    }

    /**
     * Save new PDF record
     */
    public function save_pdf($data) {
        $pdfs = $this->get_all_pdfs();
        $pdfs[] = $data;
        
        $json_data = json_encode($pdfs, JSON_PRETTY_PRINT);
        file_put_contents($this->json_file, $json_data);
        
        return true;
    }

    /**
     * Update existing PDF record
     */
    public function update_pdf($id, $data) {
        $pdfs = $this->get_all_pdfs();
        
        foreach ($pdfs as $key => $pdf) {
            if ($pdf['id'] == $id) {
                $pdfs[$key] = array_merge($pdf, $data);
                $json_data = json_encode($pdfs, JSON_PRETTY_PRINT);
                file_put_contents($this->json_file, $json_data);
                return true;
            }
        }
        
        return false;
    }

    /**
     * Delete PDF record
     */
    public function delete_pdf($id) {
        $pdfs = $this->get_all_pdfs();
        
        foreach ($pdfs as $key => $pdf) {
            if ($pdf['id'] == $id) {
                unset($pdfs[$key]);
                $pdfs = array_values($pdfs); // Re-index array
                $json_data = json_encode($pdfs, JSON_PRETTY_PRINT);
                file_put_contents($this->json_file, $json_data);
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get PDFs count
     */
    public function count_pdfs() {
        $pdfs = $this->get_all_pdfs();
        return count($pdfs);
    }

    /**
     * Search PDFs by title
     */
    public function search_pdfs($keyword) {
        $pdfs = $this->get_all_pdfs();
        $results = array();
        
        foreach ($pdfs as $pdf) {
            if (stripos($pdf['title'], $keyword) !== false) {
                $results[] = $pdf;
            }
        }
        
        return $results;
    }
}
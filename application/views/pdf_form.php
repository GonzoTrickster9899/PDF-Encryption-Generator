<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Encryption Generator - Auto Secure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
            margin-bottom: 30px;
        }
        .header-section {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        .header-section h1 {
            color: #667eea;
            font-weight: bold;
        }
        .security-badge {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        .security-badge i {
            font-size: 2em;
            display: block;
            margin-bottom: 10px;
        }
        .security-info {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .security-info h5 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .security-feature {
            display: flex;
            align-items: center;
            margin: 8px 0;
            color: #495057;
        }
        .security-feature i {
            color: #dc3545;
            margin-right: 10px;
            font-size: 1.2em;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-generate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            font-size: 1.1em;
        }
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .pdf-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
            background: linear-gradient(to right, #ffffff 0%, #f8f9fa 100%);
        }
        .pdf-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transform: translateY(-3px);
            border-color: #667eea;
        }
        .pdf-card h5 {
            color: #333;
            margin-bottom: 15px;
        }
        .pdf-security-icon {
            color: #dc3545;
            font-size: 2.5em;
            float: left;
            margin-right: 15px;
        }
        textarea {
            min-height: 150px;
        }
        .password-display {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
            color: #856404;
            margin: 15px 0;
        }
        .encryption-level {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Main Form -->
        <div class="main-container">
            <div class="header-section">
                <h1><i class="fas fa-shield-alt"></i> Maximum Security PDF Generator</h1>
                <p class="text-muted">Automatic full encryption with password "1234" - All permissions restricted</p>
            </div>

            <!-- Security Badge -->
            <div class="security-badge">
                <i class="fas fa-lock"></i>
                <h4 class="mb-0">AUTO-ENCRYPTED SYSTEM</h4>
                <p class="mb-0 mt-2">All PDFs are automatically protected with maximum security</p>
            </div>

            <!-- Security Information -->
            <div class="security-info">
                <h5><i class="fas fa-info-circle"></i> Automatic Security Features</h5>
                <div class="security-feature">
                    <i class="fas fa-key"></i>
                    <span><strong>Fixed Password:</strong> All PDFs use password "1234"</span>
                </div>
                <div class="security-feature">
                    <i class="fas fa-ban"></i>
                    <span><strong>Printing:</strong> DISABLED - No printing allowed</span>
                </div>
                <div class="security-feature">
                    <i class="fas fa-ban"></i>
                    <span><strong>Copying:</strong> DISABLED - No content copying allowed</span>
                </div>
                <div class="security-feature">
                    <i class="fas fa-ban"></i>
                    <span><strong>Modification:</strong> DISABLED - No document modification allowed</span>
                </div>
                <div class="security-feature">
                    <i class="fas fa-lock"></i>
                    <span><strong>Encryption Level:</strong> 128-bit encryption standard</span>
                </div>
            </div>

            <!-- Password Display -->
            <div class="password-display">
                <i class="fas fa-key"></i> PDF Password: <span style="font-family: monospace;">1234</span>
            </div>

            <form action="<?php echo base_url('pdf_generator/generate'); ?>" method="post" id="pdfForm">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading"></i> Document Title *
                        </label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required 
                               placeholder="Enter document title">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="content_type" class="form-label">
                            <i class="fas fa-file-alt"></i> Content Type *
                        </label>
                        <select class="form-select form-select-lg" id="content_type" name="content_type" required>
                            <option value="text">Plain Text</option>
                            <option value="html">HTML Content</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left"></i> Content *
                        </label>
                        <textarea class="form-control" id="content" name="content" required 
                                  placeholder="Enter your content here..."></textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> For HTML content, you can use tags like &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;br&gt;, &lt;p&gt;, etc.
                        </small>
                    </div>

                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-generate btn-lg">
                            <i class="fas fa-shield-alt"></i> Generate Fully Encrypted PDF
                        </button>
                        <p class="text-muted mt-2 mb-0">
                            <small><i class="fas fa-lock"></i> Password will be automatically set to: 1234</small>
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <!-- PDF List -->
        <div class="main-container">
            <div class="header-section">
                <h3><i class="fas fa-file-shield"></i> Encrypted PDF Documents</h3>
                <p class="text-muted mb-0">All documents are protected with maximum security</p>
            </div>

            <?php if(empty($pdfs)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No encrypted PDFs yet. Create your first fully protected document above!
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach($pdfs as $pdf): ?>
                        <div class="col-md-6">
                            <div class="pdf-card">
                                <i class="fas fa-file-shield pdf-security-icon"></i>
                                <div>
                                    <h5>
                                        <?php echo htmlspecialchars($pdf['title']); ?>
                                        <span class="encryption-level">
                                            <i class="fas fa-lock"></i> ENCRYPTED
                                        </span>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <small>
                                            <i class="fas fa-clock"></i> <?php echo $pdf['created_at']; ?><br>
                                            <i class="fas fa-hdd"></i> <?php echo number_format($pdf['file_size'] / 1024, 2); ?> KB | 
                                            <i class="fas fa-tag"></i> <?php echo ucfirst($pdf['content_type']); ?><br>
                                            <i class="fas fa-key"></i> <strong>Password: 1234</strong> | 
                                            <i class="fas fa-shield-alt"></i> All Permissions Restricted
                                        </small>
                                    </p>
                                    <div class="btn-group btn-group-sm mt-2">
                                        <a href="<?php echo base_url('pdf_generator/download/' . $pdf['id']); ?>" 
                                           class="btn btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a href="<?php echo base_url('pdf_generator/delete/' . $pdf['id']); ?>" 
                                           class="btn btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this encrypted PDF?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> 
                    All PDF files are protected with password "1234". You will need this password to open any downloaded file.
                    All permissions (print, copy, modify) are disabled for maximum security.
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer Information -->
        <div class="main-container">
            <div class="text-center">
                <h5 class="mb-3"><i class="fas fa-question-circle"></i> How to Use</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <i class="fas fa-edit text-primary" style="font-size: 2em;"></i>
                                <h6 class="mt-2">1. Create Content</h6>
                                <p class="small text-muted mb-0">Enter your title and content (text or HTML)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <i class="fas fa-shield-alt text-success" style="font-size: 2em;"></i>
                                <h6 class="mt-2">2. Auto-Encrypt</h6>
                                <p class="small text-muted mb-0">System automatically encrypts with password "1234"</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-info h-100">
                            <div class="card-body">
                                <i class="fas fa-download text-info" style="font-size: 2em;"></i>
                                <h6 class="mt-2">3. Download</h6>
                                <p class="small text-muted mb-0">Download and open with password "1234"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Content type change handler
        document.getElementById('content_type').addEventListener('change', function() {
            var contentArea = document.getElementById('content');
            if (this.value === 'html') {
                contentArea.placeholder = 'Enter HTML content (e.g., <h1>Title</h1><p>Paragraph</p>)';
            } else {
                contentArea.placeholder = 'Enter plain text content...';
            }
        });

        // Form submission confirmation
        document.getElementById('pdfForm').addEventListener('submit', function(e) {
            var title = document.getElementById('title').value;
            if (title.trim() === '') {
                e.preventDefault();
                alert('Please enter a document title');
                return false;
            }
        });
    </script>
</body>
</html>
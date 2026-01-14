<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Encryption Generator</title>
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
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-generate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
        }
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .permission-checkbox {
            margin-right: 15px;
        }
        .pdf-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .pdf-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        textarea {
            min-height: 150px;
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
                <h1><i class="fas fa-lock"></i> PDF Encryption Generator</h1>
                <p class="text-muted">Create secure, password-protected PDF documents with custom permissions</p>
            </div>

            <form action="<?php echo base_url('pdf_generator/generate'); ?>" method="post" id="pdfForm">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading"></i> Document Title *
                        </label>
                        <input type="text" class="form-control" id="title" name="title" required 
                               placeholder="Enter document title">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="content_type" class="form-label">
                            <i class="fas fa-file-alt"></i> Content Type *
                        </label>
                        <select class="form-select" id="content_type" name="content_type" required>
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
                        <small class="text-muted">For HTML content, you can use basic HTML tags like &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;br&gt;, etc.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_password" class="form-label">
                            <i class="fas fa-key"></i> User Password *
                        </label>
                        <input type="password" class="form-control" id="user_password" name="user_password" required 
                               placeholder="Password to open PDF" minlength="4">
                        <small class="text-muted">Required to open and view the PDF</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="owner_password" class="form-label">
                            <i class="fas fa-shield-alt"></i> Owner Password *
                        </label>
                        <input type="password" class="form-control" id="owner_password" name="owner_password" required 
                               placeholder="Password to modify permissions" minlength="4">
                        <small class="text-muted">Required to change permissions</small>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">
                            <i class="fas fa-user-lock"></i> Permissions
                        </label>
                        <div class="form-check permission-checkbox">
                            <input class="form-check-input" type="checkbox" id="allow_print" name="allow_print" checked>
                            <label class="form-check-label" for="allow_print">
                                <i class="fas fa-print"></i> Allow Printing
                            </label>
                        </div>
                        <div class="form-check permission-checkbox">
                            <input class="form-check-input" type="checkbox" id="allow_copy" name="allow_copy">
                            <label class="form-check-label" for="allow_copy">
                                <i class="fas fa-copy"></i> Allow Copy
                            </label>
                        </div>
                        <div class="form-check permission-checkbox">
                            <input class="form-check-input" type="checkbox" id="allow_modify" name="allow_modify">
                            <label class="form-check-label" for="allow_modify">
                                <i class="fas fa-edit"></i> Allow Modify
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-generate">
                            <i class="fas fa-file-pdf"></i> Generate Protected PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- PDF List -->
        <div class="main-container">
            <div class="header-section">
                <h3><i class="fas fa-list"></i> Generated PDFs</h3>
            </div>

            <?php if(empty($pdfs)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No PDFs generated yet. Create your first encrypted PDF above!
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach($pdfs as $pdf): ?>
                        <div class="col-md-6">
                            <div class="pdf-card">
                                <h5><i class="fas fa-file-pdf text-danger"></i> <?php echo htmlspecialchars($pdf['title']); ?></h5>
                                <p class="text-muted mb-2">
                                    <small>
                                        <i class="fas fa-clock"></i> <?php echo $pdf['created_at']; ?> | 
                                        <i class="fas fa-hdd"></i> <?php echo number_format($pdf['file_size'] / 1024, 2); ?> KB |
                                        <i class="fas fa-tag"></i> <?php echo ucfirst($pdf['content_type']); ?>
                                    </small>
                                </p>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo base_url('pdf_generator/download/' . $pdf['id']); ?>" 
                                       class="btn btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <a href="<?php echo base_url('pdf_generator/delete/' . $pdf['id']); ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this PDF?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
    </script>
</body>
</html>
<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo __('history_title'); ?></h1>
                <p class="lead mb-0">Discover the inspiring journey of our institution</p>
            </div>
        </div>
    </div>
</section>

<!-- History Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Timeline Introduction -->
                <div class="text-center mb-5">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <h2 class="h3 mb-4">Our Foundation Story</h2>
                            <p class="text-muted">From vision to reality, learn about the establishment and development of Burundi Adventist University.</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-lg-3 mb-4">
                                <div class="sticky-top" style="top: 2rem;">
                                    <div class="bg-light p-4 rounded">
                                        <h5 class="h6 text-uppercase text-muted mb-3">Quick Facts</h5>
                                        <div class="mb-3">
                                            <i class="bi bi-geo-alt text-primary me-2"></i>
                                            <small>Two Campuses</small>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-translate text-primary me-2"></i>
                                            <small>Bilingual Education</small>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-book text-primary me-2"></i>
                                            <small>Modern Learning</small>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-people text-primary me-2"></i>
                                            <small>Quality Education</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="content-text">
                                    <div class="text-content">
                                        <?php echo __('history_content'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Government Accreditation Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="text-center mb-4">
                            <h2 class="h3 mb-3"><?php echo __('government_accreditation_title'); ?></h2>
                            <p class="text-muted"><?php echo __('government_accreditation_subtitle'); ?></p>
                        </div>
                        
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="accreditation-document text-center">
                                            <div class="document-frame p-3 border rounded bg-light position-relative">
                                                <img src="<?php echo BASE_PATH; ?>/assets/images/autorisation_ministr1.png" 
                                                     alt="<?php echo __('ministry_authorization_1'); ?>" 
                                                     class="img-fluid rounded shadow-sm zoom-image"
                                                     style="max-height: 400px; width: auto; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal"
                                                     data-image-src="<?php echo BASE_PATH; ?>/assets/images/autorisation_ministr1.png"
                                                     data-image-title="<?php echo __('ministry_authorization_1_title'); ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <div class="text-muted p-4" style="display: none;">
                                                    <i class="fas fa-file-image fa-3x mb-3"></i>
                                                    <p><?php echo __('document_loading'); ?></p>
                                                </div>
                                                <!-- Zoom indicator -->
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-search-plus me-1"></i><?php echo __('click_to_zoom'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <h5 class="mt-3 mb-2"><?php echo __('ministry_authorization_1_title'); ?></h5>
                                            <p class="text-muted small"><?php echo __('ministry_authorization_1_desc'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="accreditation-document text-center">
                                            <div class="document-frame p-3 border rounded bg-light position-relative">
                                                <img src="<?php echo BASE_PATH; ?>/assets/images/autorisation_ministr2.png" 
                                                     alt="<?php echo __('ministry_authorization_2'); ?>" 
                                                     class="img-fluid rounded shadow-sm zoom-image"
                                                     style="max-height: 400px; width: auto; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal"
                                                     data-image-src="<?php echo BASE_PATH; ?>/assets/images/autorisation_ministr2.png"
                                                     data-image-title="<?php echo __('ministry_authorization_2_title'); ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <div class="text-muted p-4" style="display: none;">
                                                    <i class="fas fa-file-image fa-3x mb-3"></i>
                                                    <p><?php echo __('document_loading'); ?></p>
                                                </div>
                                                <!-- Zoom indicator -->
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-search-plus me-1"></i><?php echo __('click_to_zoom'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <h5 class="mt-3 mb-2"><?php echo __('ministry_authorization_2_title'); ?></h5>
                                            <p class="text-muted small"><?php echo __('ministry_authorization_2_desc'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Accreditation Info -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded p-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-certificate text-success fa-2x"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="text-success mb-1"><?php echo __('officially_accredited'); ?></h6>
                                                    <p class="mb-0 text-muted"><?php echo __('accreditation_description'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Features Section -->
                <div class="row mt-5">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-geo-alt text-primary fs-4"></i>
                                </div>
                                <h5>Two Campus Locations</h5>
                                <p class="text-muted mb-0">Jabe campus in Mukaza commune and main campus at Kivoga in Bujumbura province.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-translate text-primary fs-4"></i>
                                </div>
                                <h5>Bilingual Education</h5>
                                <p class="text-muted mb-0">Instruction in both French and English to serve the diverse African continent.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-laptop text-primary fs-4"></i>
                                </div>
                                <h5>Modern Technology</h5>
                                <p class="text-muted mb-0">PowerPoint presentations, Moodle platform, and comprehensive digital resources.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-building text-primary fs-4"></i>
                                </div>
                                <h5>Campus Facilities</h5>
                                <p class="text-muted mb-0">Dormitories, cafeteria, physical and virtual libraries for comprehensive student life.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-5">
                    <div class="bg-light p-4 rounded">
                        <h4 class="mb-3">Ready to Join Our Community?</h4>
                        <p class="text-muted mb-4">Discover more about our programs and admission process.</p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="<?php echo BASE_PATH; ?>/modules/admissions/" class="btn btn-primary">
                                <i class="bi bi-mortarboard me-2"></i><?php echo __('admissions'); ?>
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/modules/academics/" class="btn btn-outline-primary">
                                <i class="bi bi-book me-2"></i><?php echo __('academics'); ?>
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/modules/contact/" class="btn btn-outline-secondary">
                                <i class="bi bi-envelope me-2"></i><?php echo __('contact'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Zoom Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="imageModalLabel"><?php echo __('document_viewer'); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <div class="image-container text-center" style="overflow: auto; max-height: 80vh;">
                    <img id="modalImage" src="" alt="" class="img-fluid" style="max-width: none; cursor: grab;">
                </div>
                <!-- Zoom Controls -->
                <div class="position-absolute top-0 start-0 m-3">
                    <div class="btn-group-vertical" role="group">
                        <button type="button" class="btn btn-light btn-sm" id="zoomIn" title="<?php echo __('zoom_in'); ?>">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-sm" id="zoomOut" title="<?php echo __('zoom_out'); ?>">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-sm" id="resetZoom" title="<?php echo __('reset_zoom'); ?>">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </button>
                    </div>
                </div>
                <!-- Download Button -->
                <div class="position-absolute top-0 end-0 m-3">
                    <a id="downloadBtn" href="" download class="btn btn-success btn-sm" title="<?php echo __('download_document'); ?>">
                        <i class="fas fa-download me-1"></i><?php echo __('download'); ?>
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0 bg-dark">
                <p class="text-white-50 mb-0 small">
                    <i class="fas fa-info-circle me-1"></i>
                    <?php echo __('zoom_instructions'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.zoom-image:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}

.modal-xl {
    max-width: 95vw;
}

#modalImage {
    transition: transform 0.2s ease;
}

#modalImage:active {
    cursor: grabbing;
}

.image-container {
    background: #000;
    border-radius: 0.375rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    const downloadBtn = document.getElementById('downloadBtn');
    const zoomInBtn = document.getElementById('zoomIn');
    const zoomOutBtn = document.getElementById('zoomOut');
    const resetZoomBtn = document.getElementById('resetZoom');
    
    let currentScale = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;
    
    // Handle modal show event
    imageModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const imageSrc = button.getAttribute('data-image-src');
        const imageTitle = button.getAttribute('data-image-title');
        
        modalImage.src = imageSrc;
        modalImage.alt = imageTitle;
        modalTitle.textContent = imageTitle;
        downloadBtn.href = imageSrc;
        downloadBtn.download = imageTitle + '.png';
        
        // Reset zoom
        currentScale = 1;
        modalImage.style.transform = 'scale(1)';
    });
    
    // Zoom functionality
    zoomInBtn.addEventListener('click', function() {
        currentScale = Math.min(currentScale + 0.25, 3);
        modalImage.style.transform = `scale(${currentScale})`;
    });
    
    zoomOutBtn.addEventListener('click', function() {
        currentScale = Math.max(currentScale - 0.25, 0.5);
        modalImage.style.transform = `scale(${currentScale})`;
    });
    
    resetZoomBtn.addEventListener('click', function() {
        currentScale = 1;
        modalImage.style.transform = 'scale(1)';
        const container = modalImage.parentElement;
        container.scrollLeft = 0;
        container.scrollTop = 0;
    });
    
    // Mouse wheel zoom
    modalImage.addEventListener('wheel', function(e) {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        currentScale = Math.max(0.5, Math.min(3, currentScale + delta));
        modalImage.style.transform = `scale(${currentScale})`;
    });
    
    // Drag functionality for zoomed images
    const container = modalImage.parentElement;
    
    modalImage.addEventListener('mousedown', function(e) {
        if (currentScale > 1) {
            isDragging = true;
            modalImage.style.cursor = 'grabbing';
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
        }
    });
    
    container.addEventListener('mouseleave', function() {
        isDragging = false;
        modalImage.style.cursor = currentScale > 1 ? 'grab' : 'default';
    });
    
    container.addEventListener('mouseup', function() {
        isDragging = false;
        modalImage.style.cursor = currentScale > 1 ? 'grab' : 'default';
    });
    
    container.addEventListener('mousemove', function(e) {
        if (!isDragging || currentScale <= 1) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const y = e.pageY - container.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = (y - startY) * 2;
        container.scrollLeft = scrollLeft - walkX;
        container.scrollTop = scrollTop - walkY;
    });
    
    // Update cursor based on zoom level
    function updateCursor() {
        modalImage.style.cursor = currentScale > 1 ? 'grab' : 'default';
    }
    
    // Call updateCursor when zoom changes
    const observer = new MutationObserver(updateCursor);
    observer.observe(modalImage, { attributes: true, attributeFilter: ['style'] });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
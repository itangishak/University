<?php
require_once '../../config/config.php';
require_once '../../includes/language.php';

$pageTitle = __('leadership_title');
$pageDescription = __('leadership_description');
?>

<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo __('site_title'); ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css">
    
    <style>
        .leadership-hero {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
        }
        
        .leadership-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .leadership-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .rector-profile {
            border: 3px solid #ffd700;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        
        .profile-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .profile-content {
            padding: 30px;
        }
        
        .profile-name {
            color: #1e3c72;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .profile-title {
            color: #ffd700;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .profile-bio {
            color: #666;
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        .section-title {
            color: #1e3c72;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .section-subtitle {
            color: #666;
            font-size: 1.2rem;
            text-align: center;
            margin-bottom: 50px;
        }
        
        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        @media (max-width: 768px) {
            .leadership-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-image {
                height: 250px;
            }
            
            .profile-name {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include '../../includes/header.php'; ?>

    <!-- Leadership Hero Section -->
    <section class="leadership-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-users-cog me-3"></i>
                        <?php echo __('leadership_title'); ?>
                    </h1>
                    <p class="lead mb-0">
                        <?php echo __('leadership_subtitle'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Content -->
    <section class="py-5">
        <div class="container">
            <!-- Rector Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title"><?php echo __('rector_section_title'); ?></h2>
                    <p class="section-subtitle"><?php echo __('rector_section_subtitle'); ?></p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="leadership-card rector-profile">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo BASE_PATH; ?>/assets/images/rector.jpg" 
                                     alt="<?php echo __('rector_name'); ?>" 
                                     class="profile-image"
                                     onerror="this.src='<?php echo BASE_PATH; ?>/assets/images/placeholder-person.jpg'">
                            </div>
                            <div class="col-md-8">
                                <div class="profile-content">
                                    <h3 class="profile-name"><?php echo __('rector_name'); ?></h3>
                                    <p class="profile-title">
                                        <i class="fas fa-crown me-2"></i>
                                        <?php echo __('rector_title'); ?>
                                    </p>
                                    <div class="profile-bio">
                                        <?php echo __('rector_biography'); ?>
                                    </div>
                                    
                                    <!-- Contact Information -->
                                    <div class="mt-4 pt-3 border-top">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-2">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    <strong><?php echo __('email'); ?>:</strong> jnsabiyaremye@uab.edu.bi
                                                </p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-2">
                                                    <i class="fas fa-phone text-primary me-2"></i>
                                                    <strong><?php echo __('phone'); ?>:</strong> +257 77740851
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Future Leadership Team Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h2 class="section-title"><?php echo __('leadership_team_title'); ?></h2>
                    <p class="section-subtitle"><?php echo __('leadership_team_subtitle'); ?></p>
                </div>
            </div>
            
            <div class="leadership-grid">
                <!-- Placeholder for future leadership members -->
                <div class="leadership-card">
                    <div class="profile-content text-center">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted"><?php echo __('coming_soon'); ?></h4>
                        <p class="text-muted"><?php echo __('more_leadership_profiles_coming'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

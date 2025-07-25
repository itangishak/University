<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('site_name'); ?> - <?php echo __('page_title'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css">
     <link rel="icon" type="image/x-icon" href="<?php echo BASE_PATH; ?>/assets/images/icon.ico">
    <!-- WCAG 2.2 AA compliance styles -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/accessibility.css">
</head>
<body>
    <header>
        <!-- Top Info Bar -->
        <div class="top-info-bar">
            <div class="container">
                <div class="top-info-content">
                    <div class="contact-info">
                        <span class="info-item">
                            <i class="fas fa-envelope"></i>
                            info@bau.edu.bi
                        </span>
                       
                    </div>
                    <div class="top-actions">
                        <!-- Language Switcher -->
                        <div class="language-switcher">
                            <a href="?lang=fr" data-lang="fr" class="lang-btn <?php echo $current_lang == 'fr' ? 'active' : ''; ?>" title="Français">
                                <img src="<?php echo BASE_PATH; ?>/assets/images/french.png" alt="FR">
                                <span>FR</span>
                            </a>
                            <a href="?lang=en" data-lang="en" class="lang-btn <?php echo $current_lang == 'en' ? 'active' : ''; ?>" title="English">
                                <img src="<?php echo BASE_PATH; ?>/assets/images/english.png" alt="EN">
                                <span>EN</span>
                            </a>
                        </div>
                        <!-- Portal Button -->
                        <a href="/university/modules/admin/login/login.php" class="portal-btn">
                            <i class="fas fa-user-circle"></i>
                            <?php echo __('portal_login'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-professional">
            <div class="container">
                <!-- Brand/Logo -->
                <a class="navbar-brand" href="<?php echo BASE_PATH; ?>/index.php">
                    <div class="brand-container">
                        <img src="<?php echo BASE_PATH; ?>/assets/images/logouab.png" alt="<?php echo __('site_name'); ?>" class="brand-logo">
                        <div class="brand-text">
                            <span class="brand-name">BAU</span>
                            <span class="brand-subtitle">Burundi Adventist University</span>
                        </div>
                    </div>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                </button>
                
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/index.php">
                                <i class="fas fa-home nav-icon"></i>
                                <?php echo __('menu_home'); ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAbout" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-info-circle nav-icon"></i>
                                <?php echo __('menu_about'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-modern">
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/about/history.php">
                                        <i class="fas fa-history"></i>
                                        <?php echo __('menu_history'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/about/mission.php">
                                        <i class="fas fa-bullseye"></i>
                                        <?php echo __('menu_mission'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/leadership/leadership.php">
                                        <i class="fas fa-users-cog"></i>
                                        <?php echo __('menu_leadership'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAcademics" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-graduation-cap nav-icon"></i>
                                <?php echo __('menu_academics'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-modern">
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/faculty/faculties.php">
                                        <i class="fas fa-building"></i>
                                        <?php echo __('menu_faculties'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/admission/admissions.php">
                                        <i class="fas fa-user-graduate"></i>
                                        <?php echo __('menu_admissions'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/library/libraries.php">
                                        <i class="fas fa-book"></i>
                                        <?php echo __('menu_libraries'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_PATH; ?>modules/question/questions.php">
                                        <i class="fas fa-question-circle"></i>
                                        <?php echo __('menu_questions'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>modules/news/news.php">
                                <i class="fas fa-newspaper nav-icon"></i>
                                <?php echo __('menu_news'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>modules/contact/contact.php">
                                <i class="fas fa-envelope nav-icon"></i>
                                <?php echo __('menu_contact'); ?>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Search Form -->
                    <div class="navbar-search">
                        <form class="search-form" action="<?php echo BASE_PATH; ?>search.php" method="get">
                            <div class="search-input-group">
                                <input class="search-input" type="search" name="q" placeholder="<?php echo __('search_placeholder'); ?>" aria-label="Search">
                                <button class="search-btn" type="submit" aria-label="<?php echo __('search_button'); ?>">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?php echo BASE_PATH; ?>/assets/js/main.js"></script>

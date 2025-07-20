<?php require_once 'language.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('site_name'); ?> - <?php echo __('page_title'); ?></title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
     <link rel="icon" type="image/x-icon" href="icon.ico">
    <!-- WCAG 2.2 AA compliance styles -->
    <link rel="stylesheet" href="./assets/css/accessibility.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="./assets/images/icon.ico" alt="<?php echo __('site_name'); ?>" height="60">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/"><?php echo __('menu_home'); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAbout" role="button" data-bs-toggle="dropdown">
                                <?php echo __('menu_about'); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/about/history"><?php echo __('menu_history'); ?></a></li>
                                <li><a class="dropdown-item" href="/about/mission"><?php echo __('menu_mission'); ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAcademics" role="button" data-bs-toggle="dropdown">
                                <?php echo __('menu_academics'); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/faculties"><?php echo __('menu_faculties'); ?></a></li>
                                <li><a class="dropdown-item" href="/programs"><?php echo __('menu_programs'); ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/news"><?php echo __('menu_news'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact"><?php echo __('menu_contact'); ?></a>
                        </li>
                    </ul>
                    
                    <!-- Language Switcher -->
                    <select class="language-selector form-select ms-3" onchange="window.location.href='?lang='+this.value">
                        <option value="fr" <?php echo $current_lang == 'fr' ? 'selected' : ''; ?>>Français</option>
                        <option value="en" <?php echo $current_lang == 'en' ? 'selected' : ''; ?>>English</option>
                    </select>
                    
                    <!-- Search Form -->
                    <form class="d-flex ms-3" action="/search" method="get">
                        <input class="form-control me-2" type="search" name="q" placeholder="<?php echo __('search_placeholder'); ?>">
                        <button class="btn btn-outline-light" type="submit"><?php echo __('search_button'); ?></button>
                    </form>
                    
                    <!-- Login/Portal Button -->
                    <a href="/portal" class="btn btn-outline-light ms-3"><?php echo __('portal_login'); ?></a>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container py-4">

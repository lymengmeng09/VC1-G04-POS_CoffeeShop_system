<!DOCTYPE html>
<html lang="<?php echo LanguageHelper::getCurrentLang(); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Mazer Admin Dashboard</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="/views/assets/css/view.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/views/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
  <link rel="stylesheet" href="/views/assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="/views/assets/css/app.css">
  <link rel="stylesheet" href="/views/assets/css/setting.css">
  <link rel="stylesheet" href="/views/assets/css/user.css">
  <link rel="stylesheet" href="/views/assets/css/add-product.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="/views/assets/vendors/iconly/bold.css">
  <link rel="shortcut icon" href="/views/assets/images/favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="/views/assets/css/edit-product.css">
  <link rel="stylesheet" href="/views/assets/css/list-product.css">
  <link rel="stylesheet" href="/views/assets/css/Dashboard.css">
  <link rel="stylesheet" href="/views/assets/css/navbar.css">
  <link rel="stylesheet" href="/views/assets/css/create.css">
  <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/views/assets/css/purchase.css">
  <link rel="stylesheet" href="/views/assets/css/history.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<style>
  html[lang="km"] * {
    font-family: 'Siemreap', sans-serif;
    line-height: 1.6;
  }
  
  /* Fix for icon alignment in different languages */
  .dropdown-item, .edit-link {
    display: flex !important;
    align-items: center !important;
  }
  
  .dropdown-item i, .edit-link i, .btn i {
    display: inline-flex !important;
    justify-content: center !important;
    align-items: center !important;
    width: 20px !important; /* Fixed width for icon container */
    height: 20px !important; /* Fixed height for icon container */
    margin-right: 8px !important;
  }
  
  /* Fix for button with icon alignment */
  .btn-outline-secondary {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
  }
  
  /* Fix for dropdown toggle icon spacing */
  .dropdown-toggle::after {
    margin-left: 8px !important;
  }
  
  /* Ensure consistent spacing in navbar icons */
  .navbar .btn i, .navbar .dropdown-item i {
    margin-right: 8px !important;
  }
  
  /* Fix for language switcher icon alignment */
  .language-switcher .btn i {
    margin-right: 5px !important;
  }
</style>
<body>
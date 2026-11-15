<?php
/**
 * Shared Header Layout
 * RTIH TaskFlow
 */
$pageTitle = $pageTitle ?? 'TaskFlow – Plan. Track. Progress.';
$currentUser = auth_user();
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-background">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/images/favicon.png">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/public/assets/images/logo-icon.png">

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "on-primary": "#ffffff",
              "secondary": "#4d6700",
              "primary-fixed-dim": "#abcec8",
              "surface-bright": "#eefdf6",
              "secondary-container": "#ccf077",
              "inverse-surface": "#26332f",
              "surface": "#eefdf6",
              "primary": "#012421",
              "inverse-on-surface": "#e5f4ee",
              "outline": "#717977",
              "inverse-primary": "#abcec8",
              "tertiary-container": "#b9aa81",
              "on-surface": "#111e1a",
              "tertiary-fixed-dim": "#d5c59a",
              "surface-container-lowest": "#ffffff",
              "on-error-container": "#93000a",
              "on-tertiary": "#ffffff",
              "on-surface-variant": "#414847",
              "surface-tint": "#446460",
              "surface-container-high": "#ddece5",
              "on-primary-container": "#82a49e",
              "primary-fixed": "#c6eae3",
              "secondary-fixed-dim": "#b0d35f",
              "on-tertiary-fixed-variant": "#514625",
              "on-primary-fixed": "#00201d",
              "tertiary-fixed": "#f2e1b5",
              "on-secondary-fixed": "#151f00",
              "secondary-fixed": "#ccf077",
              "on-secondary": "#ffffff",
              "error": "#ba1a1a",
              "surface-container": "#e2f1eb",
              "primary-container": "#193a36",
              "on-secondary-fixed-variant": "#394d00",
              "surface-variant": "#d7e6df",
              "on-tertiary-container": "#493e1e",
              "background": "#eefdf6",
              "surface-container-highest": "#d7e6df",
              "surface-dim": "#ceddd7",
              "on-background": "#111e1a",
              "on-error": "#ffffff",
              "on-tertiary-fixed": "#231b01",
              "outline-variant": "#c1c8c6",
              "on-primary-fixed-variant": "#2c4c48",
              "surface-container-low": "#e8f7f0",
              "tertiary": "#695e3a",
              "error-container": "#ffdad6",
              "on-secondary-container": "#526d00"
            },
            borderRadius: {
              "DEFAULT": "0.25rem",
              "lg": "0.5rem",
              "xl": "0.75rem",
              "full": "9999px"
            },
            fontFamily: {
              sans: ["Inter", "sans-serif"],
              heading: ["Plus Jakarta Sans", "sans-serif"]
            }
          }
        }
    };
    window.BASE_URL = "<?= BASE_URL ?>";
    </script>
    <style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .material-symbols-fill {
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      body {
        font-family: 'Inter', sans-serif;
      }
      h1, h2, h3, .font-heading {
        font-family: 'Plus Jakarta Sans', sans-serif;
      }
    </style>
</head>
<body class="h-full text-on-surface antialiased bg-background">

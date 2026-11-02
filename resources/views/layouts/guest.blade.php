<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            html, body {
                height: 100%;
                overflow: hidden;
            }
            body {
                background-color: #e8eaed;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* ========== LOGIN CARD (Medium) ========== */
            .login-card {
                border: none;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.15);
                width: 900px;
                max-width: 95vw;
                height: 520px;
                max-height: 90vh;
            }

            /* ========== REGISTER WIZARD CARD ========== */
            .wizard-card {
                border: none;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.15);
                width: 1000px;
                max-width: 95vw;
                height: 580px;
                max-height: 92vh;
            }

            /* ========== FORM SIDE ========== */
            .form-side {
                padding: 30px 45px;
                background: #ffffff;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                overflow-y: auto;
            }

            /* ========== INFO SIDE ========== */
            .info-side {
                background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 50%, #1e3a5f 100%);
                padding: 40px 45px;
                color: white;
                position: relative;
                overflow: hidden;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .info-side::before {
                content: '';
                position: absolute;
                top: -100px;
                right: -100px;
                width: 300px;
                height: 300px;
                background: rgba(255,255,255,0.06);
                border-radius: 50%;
            }
            .info-side::after {
                content: '';
                position: absolute;
                bottom: -80px;
                left: -80px;
                width: 250px;
                height: 250px;
                background: rgba(255,255,255,0.04);
                border-radius: 50%;
            }

            /* ========== HEADINGS ========== */
            .welcome-heading {
                font-family: 'Georgia', 'Times New Roman', serif;
                font-size: 2.2rem;
                font-weight: 700;
                color: #1e3a5f;
                margin-bottom: 6px;
                text-align: center;
            }
            .welcome-subtitle {
                font-size: 0.9rem;
                color: #6c757d;
                margin-bottom: 20px;
                text-align: center;
            }

            /* ========== LABELS ========== */
            .form-label-custom {
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: #1e3a5f;
                margin-bottom: 6px;
                display: block;
            }

            /* ========== INPUTS WITH LEFT ICON ========== */
            .input-group-custom {
                position: relative;
                display: flex;
                align-items: center;
            }
            .input-icon-box {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 42px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-right: 1.5px solid #e2e8f0;
                color: #adb5bd;
                font-size: 0.95rem;
                z-index: 10;
            }
            .form-control-custom {
                border: 1.5px solid #e2e8f0;
                border-radius: 10px;
                padding: 10px 40px 10px 52px;
                font-size: 0.9rem;
                height: 44px;
                width: 100%;
                transition: all 0.2s ease;
                background: #fff;
            }
            .form-control-custom:focus {
                border-color: #2c5282;
                box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
                outline: none;
            }

            /* ========== SELECT DROPDOWN ========== */
            select.form-control-custom {
                appearance: auto;
                -webkit-appearance: auto;
                -moz-appearance: auto;
                background-image: none;
                padding-right: 30px;
            }

            /* ========== REMEMBER ME ========== */
            .remember-check {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 12px 0;
            }
            .remember-check input {
                width: 16px;
                height: 16px;
                border: 1.5px solid #d1d5db;
                border-radius: 4px;
                accent-color: #2c5282;
            }
            .remember-check label {
                font-size: 0.85rem;
                color: #6c757d;
                cursor: pointer;
            }

            /* ========== FULL WIDTH BUTTON ========== */
            .btn-full {
                width: 100%;
                background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
                border: none;
                border-radius: 10px;
                padding: 12px;
                font-weight: 700;
                font-size: 0.85rem;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: white;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(30, 58, 95, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-top: 8px;
            }
            .btn-full:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(30, 58, 95, 0.4);
            }

            /* ========== LINKS ========== */
            .link-forgot {
                color: #6c757d;
                text-decoration: none;
                font-size: 0.8rem;
                transition: color 0.3s;
            }
            .link-forgot:hover {
                color: #2c5282;
            }
            .link-signup {
                color: #2c5282;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.8rem;
            }

            /* ========== FEATURES ========== */
            .brand-name {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 18px;
                position: relative;
                z-index: 1;
            }
            .brand-desc {
                font-size: 0.85rem;
                line-height: 1.7;
                opacity: 0.9;
                margin-bottom: 25px;
                position: relative;
                z-index: 1;
            }
            .feature-item {
                display: flex;
                align-items: center;
                margin-bottom: 16px;
                position: relative;
                z-index: 1;
            }
            .feature-icon-box {
                width: 38px;
                height: 38px;
                background: rgba(255,255,255,0.15);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 14px;
                font-size: 1rem;
            }
            .feature-text {
                font-size: 0.9rem;
                font-weight: 500;
            }

            /* ========== WIZARD STEPS ========== */
            .wizard-steps {
                display: flex;
                margin-bottom: 15px;
                padding: 0 30px;
                position: relative;
            }
            .wizard-step {
                flex: 1;
                text-align: center;
                position: relative;
            }
            .wizard-step::after {
                content: '';
                position: absolute;
                top: 16px;
                left: 50%;
                width: 100%;
                height: 2px;
                background: #e2e8f0;
                z-index: 0;
            }
            .wizard-step:last-child::after {
                display: none;
            }
            .step-circle {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: #e2e8f0;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 6px;
                font-size: 0.8rem;
                font-weight: 700;
                position: relative;
                z-index: 1;
                transition: all 0.3s ease;
            }
            .wizard-step.active .step-circle {
                background: #2c5282;
                color: white;
            }
            .wizard-step.completed .step-circle {
                background: #28a745;
                color: white;
            }
            .step-label {
                font-size: 0.7rem;
                color: #6c757d;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .wizard-step.active .step-label {
                color: #2c5282;
                font-weight: 600;
            }

            /* ========== WIZARD CONTENT ========== */
            .wizard-content {
                display: none;
            }
            .wizard-content.active {
                display: block;
            }
            .wizard-nav {
                display: flex;
                justify-content: space-between;
                margin-top: 14px;
            }
            .btn-wizard {
                padding: 8px 24px;
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.2s ease;
            }
            .btn-wizard-next {
                background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
                border: none;
                color: white;
                box-shadow: 0 3px 10px rgba(30, 58, 95, 0.3);
            }
            .btn-wizard-prev {
                background: #e2e8f0;
                border: none;
                color: #6c757d;
            }

            /* ========== SPACING ========== */
            .mb-2-custom {
                margin-bottom: 0.6rem !important;
            }

            /* ========== RESPONSIVE ========== */
            @media (max-width: 991px) {
                .login-card, .wizard-card {
                    height: auto;
                    max-height: 95vh;
                }
                .form-side {
                    padding: 25px;
                }
                .info-side {
                    padding: 25px;
                }
                .welcome-heading {
                    font-size: 1.6rem;
                }
            }
        </style>
        <link rel="stylesheet" href="{{ asset('frontend/css/premium.css') }}">
    </head>
    <body>
        {{ $slot }}

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

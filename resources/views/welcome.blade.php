<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOM Product Project - Welcome</title>
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* Brand Colors from project */
            --primary-color: #087c36;
            --secondary-color: #116128;
            --success-color: #087c36;
            --danger-color: #e53e3e;
            --warning-color: #dd6b20;
            --info-color: #3b82f6;
            --light-color: #f8f9fa;
            --dark-color: #343a40;

            /* Light Theme Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #f1f3f5;
            --text-primary: #343a40;
            --text-secondary: #495057;
            --text-tertiary: #868e96;
            --border-color: #e9ecef;
            --card-bg: #ffffff;
            --header-bg: #ffffff;

            /* Grays & Whites */
            --gray-50: #f8f9fa;
            --gray-100: #f1f3f5;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #868e96;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --white: #ffffff;
            --black: #000000;

            /* Modern UI Properties */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition-fast: all 0.2s ease-in-out;
            --transition-normal: all 0.3s ease-in-out;
            --transition-slow: all 0.5s ease-in-out;

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            --gradient-light: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            --gradient-dark: linear-gradient(135deg, var(--gray-800) 0%, var(--gray-900) 100%);
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --bg-primary: #0f1419;
            --bg-secondary: #1a1f2e;
            --bg-tertiary: #252d3d;
            --text-primary: #e6e8eb;
            --text-secondary: #c2c8d1;
            --text-tertiary: #8b92a6;
            --border-color: #2a2f3a;
            --card-bg: #1a1f2e;
            --header-bg: #0f1419;
            --gradient-light: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);

            /* Soft grays for dark mode */
            --gray-50: #2a2f3a;
            --gray-100: #1a1f2e;
            --gray-200: #2a2f3a;
            --gray-300: #3d4451;
            --gray-400: #525866;
            --gray-500: #6b7280;
            --gray-600: #9ca3af;
            --gray-700: #d1d5db;
            --gray-800: #e5e7eb;
            --gray-900: #f9fafb;
        }

        /* Auto theme detection */
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg-primary: #0f1419;
                --bg-secondary: #1a1f2e;
                --bg-tertiary: #252d3d;
                --text-primary: #e6e8eb;
                --text-secondary: #c2c8d1;
                --text-tertiary: #8b92a6;
                --border-color: #2a2f3a;
                --card-bg: #1a1f2e;
                --header-bg: #0f1419;
                --gradient-light: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);

                --gray-50: #2a2f3a;
                --gray-100: #1a1f2e;
                --gray-200: #2a2f3a;
                --gray-300: #3d4451;
                --gray-400: #525866;
                --gray-500: #6b7280;
                --gray-600: #9ca3af;
                --gray-700: #d1d5db;
                --gray-800: #e5e7eb;
                --gray-900: #f9fafb;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Header */
        .header {
            background: var(--header-bg);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .nav {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }

        .nav-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 0.5rem;
            gap: 4px;
            position: absolute;
            right: 2rem;
        }

        .nav-toggle span {
            width: 25px;
            height: 3px;
            background: var(--text-primary);
            transition: var(--transition-fast);
            transform-origin: center;
        }

        .nav-toggle.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            position: absolute;
            left: 2rem;
        }

        .logo i {
            font-size: 2rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            transition: var(--transition-normal);
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-fast);
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: var(--transition-fast);
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-light);
            padding: 4rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="%23087c36" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--white);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-full);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero .subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }

        .btn {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition-fast);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1rem;
            text-decoration: none;
            min-width: 160px;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            color: white;
        }

        .btn-secondary {
            background: var(--bg-primary);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            background: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .stat {
            text-align: center;
            padding: 1.5rem 1rem;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .stat:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: clamp(0.875rem, 2vw, 1rem);
        }

        /* About Section */
        .about {
            padding: 4rem 2rem;
            background: var(--bg-secondary);
        }

        .about-content {
            margin-top: 2rem;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .about-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid var(--border-color);
            transition: var(--transition-fast);
        }

        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .about-card.mission .about-icon { color: #e74c3c; }
        .about-card.vision .about-icon { color: #3498db; }
        .about-card.goal .about-icon { color: #f39c12; }

        .about-icon {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
        }

        .about-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .about-card p {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .about-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .detail-section {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
        }

        .detail-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-section h3::before {
            content: '▶';
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .detail-section p {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .about-features {
            list-style: none;
            padding: 0;
        }

        .about-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .about-features li i {
            color: var(--primary-color);
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Features Section */
        .features {
            padding: 4rem 2rem;
            background: var(--bg-primary);
            transition: background-color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: clamp(1rem, 2.5vw, 1.125rem);
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feature {
            padding: 1.5rem;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .feature:hover {
            background: var(--bg-primary);
            box-shadow: var(--shadow-lg);
            transform: translateY(-5px);
        }

        .feature h3 {
            font-size: clamp(1.1rem, 2.5vw, 1.25rem);
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature p {
            color: var(--text-secondary);
            line-height: 1.6;
            font-size: clamp(0.875rem, 2vw, 1rem);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .feature-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .feature h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .feature p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* Tech Stack */
        .tech-stack {
            padding: 4rem 2rem;
            background: var(--bg-secondary);
        }

        .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .tech-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            text-align: center;
            transition: var(--transition-fast);
            border: 1px solid var(--border-color);
        }

        .tech-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .tech-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .tech-card.frontend .tech-icon { color: #61dafb; }
        .tech-card.backend .tech-icon { color: #ff2d20; }
        .tech-card.database .tech-icon { color: #336791; }
        .tech-card.mobile .tech-icon { color: #3ddc84; }

        .tech-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .tech-card p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Architecture */
        .architecture {
            padding: 4rem 2rem;
            background: var(--bg-primary);
        }

        .architecture-diagram {
            background: var(--bg-secondary);
            border-radius: var(--radius-lg);
            padding: 3rem;
            margin-top: 2rem;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .diagram-flow {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .diagram-box {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            min-width: 120px;
            text-align: center;
            transition: var(--transition-fast);
            border: 1px solid var(--border-color);
        }

        .diagram-box:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .diagram-arrow {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        /* Quick Start */
        .quick-start {
            padding: 4rem 2rem;
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .quick-start .section-title h2,
        .quick-start .section-title p {
            color: var(--text-primary);
        }

        .command-box {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin: 2rem 0;
            border-left: 4px solid var(--primary-color);
            border: 1px solid var(--border-color);
            position: relative;
            overflow-x: auto;
        }

        .command-box::before {
            content: '$ ';
            color: var(--primary-color);
            font-weight: 600;
        }

        .command {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.8;
        }

        .copy-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 0.75rem;
            transition: var(--transition-fast);
        }

        .copy-btn:hover {
            background: var(--secondary-color);
        }

        /* Footer */
        .footer {
            background: var(--bg-primary);
            color: var(--text-secondary);
            padding: 3rem 2rem 2rem;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--text-tertiary);
            text-decoration: none;
            transition: var(--transition-fast);
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-tertiary);
            transition: var(--transition-fast);
        }

        .social-links a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Navigation Controls */
        .nav-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: absolute;
            right: 2rem;
        }

        .control-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: var(--transition-fast);
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            min-height: 40px;
        }

        .control-btn:hover {
            background: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        .theme-toggle {
            width: 45px;
            justify-content: center;
            font-size: 1.2rem;
        }

        .lang-btn {
            width: 45px;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Page Up Button */
        .page-up {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius-full);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: var(--shadow-lg);
            transition: var(--transition-fast);
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            visibility: hidden;
        }

        .page-up.visible {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        .page-up:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        .page-up:active {
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .nav-controls {
                gap: 0.25rem;
                right: 1.5rem;
            }

            .logo {
                left: 1.5rem;
            }

            .control-btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
                min-height: 36px;
            }

            .theme-toggle {
                width: 40px;
                font-size: 1.1rem;
            }

            .lang-btn {
                width: 40px;
                font-size: 1.1rem;
            }

            .page-up {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .nav {
                padding: 1rem;
                justify-content: space-between;
            }

            .logo {
                position: static;
                order: 1;
            }

            .nav-controls {
                position: static;
                order: 2;
                gap: 0.25rem;
            }

            .nav-toggle {
                display: flex;
                order: 3;
                position: static;
            }

            .control-btn {
                padding: 0.35rem 0.5rem;
                font-size: 0.75rem;
                min-height: 32px;
            }

            .theme-toggle {
                width: 35px;
                font-size: 1rem;
            }

            .lang-btn {
                width: 35px;
                font-size: 1rem;
            }

            .page-up {
                bottom: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--header-bg);
                flex-direction: column;
                gap: 0;
                padding: 1rem 2rem 2rem;
                box-shadow: var(--shadow-lg);
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                border-top: 1px solid var(--border-color);
            }

            .nav-links.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            .nav-links li {
                width: 100%;
                text-align: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .nav-links li:last-child {
                border-bottom: none;
            }

            .nav-links a {
                display: block;
                width: 100%;
                padding: 0.5rem;
                font-size: 1.1rem;
            }

            .hero {
                padding: 3rem 1rem;
            }

            .hero h1 {
                font-size: clamp(2rem, 6vw, 2.5rem);
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                max-width: 250px;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
                margin-top: 2rem;
            }

            .stat {
                padding: 1rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .about-details {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .about-card,
            .detail-section {
                padding: 1.25rem;
            }

            .feature {
                padding: 1.25rem;
            }

            .tech-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .tech-card {
                padding: 1.5rem 1rem;
            }

            .diagram-flow {
                flex-direction: column;
                gap: 1rem;
            }

            .diagram-arrow {
                transform: rotate(90deg);
            }

            .diagram-box {
                padding: 1rem;
                min-width: 80px;
            }

            .architecture-diagram {
                padding: 1.5rem;
            }

            .features, .tech-stack, .architecture, .quick-start {
                padding: 3rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .nav {
                padding: 0.75rem;
            }

            .logo {
                font-size: 1.25rem;
            }

            .logo i {
                font-size: 1.5rem;
            }

            .hero {
                padding: 2rem 0.75rem;
            }

            .stats {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .stat {
                padding: 0.875rem;
            }

            .feature {
                padding: 1rem;
            }

            .about-card,
            .detail-section {
                padding: 1rem;
            }

            .about-grid {
                gap: 0.75rem;
            }

            .about-details {
                gap: 0.75rem;
            }

            .tech-card {
                padding: 1.25rem 0.75rem;
            }

            .features, .tech-stack, .architecture, .quick-start {
                padding: 2rem 0.75rem;
            }

            .command-box {
                padding: 1rem;
                font-size: 0.75rem;
            }

            .copy-btn {
                padding: 0.25rem;
                font-size: 0.6rem;
            }

            .page-up {
                bottom: 10px;
                right: 10px;
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <a href="#" class="logo">
                <i class="fas fa-shopping-cart"></i>
                <span>DOM Product</span>
            </a>
            <ul class="nav-links" id="nav-links">
                <li><a href="#loyiha" data-key="about">Loyiha Haqida</a></li>
                <li><a href="#imkoniyatlar" data-key="features">Imkoniyatlar</a></li>
                <li><a href="#texnologiyalar" data-key="technologies">Texnologiyalar</a></li>
                <li><a href="#arxitektura" data-key="architecture">Arxitektura</a></li>
                <li><a href="#boshlash" data-key="quickstart">Boshlash</a></li>
            </ul>

            <!-- Navigation Controls -->
            <div class="nav-controls">
                <button class="control-btn theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
                    <span id="theme-icon">🌙</span>
                </button>
                <button class="control-btn lang-btn" onclick="toggleLanguage()" title="Change Language">
                    <span id="flag">🇺🇿</span>
                </button>
            </div>

            <div class="nav-toggle" onclick="toggleNav()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content fade-in-up">
            <div class="hero-badge">
                <i class="fas fa-rocket"></i>
                <span data-key="version">Version 1.3.5 - Professional</span>
            </div>
            <h1 data-key="hero-title">🛒 DOM Product Project</h1>
            <p class="subtitle" data-key="hero-subtitle">
                Zamonaviy texnologiyalar asosida qurilgan professional elektron tijorat platformasi
            </p>
            <div class="cta-buttons">
                <a href="#boshlash" class="btn btn-primary">
                    <i class="fas fa-play"></i>
                    <span data-key="get-started">Boshlash</span>
                </a>
                <a href="https://github.com/urinboy/dom-product-project" class="btn btn-secondary" target="_blank">
                    <i class="fab fa-github"></i>
                    <span data-key="view-github">GitHub'da Ko'rish</span>
                </a>
            </div>

            <!-- Project Stats -->
            <div class="stats">
                <div class="stat floating">
                    <div class="stat-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="stat-number">4</div>
                    <div class="stat-label" data-key="stat-platforms">Platformalar</div>
                </div>
                <div class="stat floating" style="animation-delay: 0.2s;">
                    <div class="stat-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-number">17</div>
                    <div class="stat-label" data-key="stat-models">Ma'lumot Modellari</div>
                </div>
                <div class="stat floating" style="animation-delay: 0.4s;">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">4</div>
                    <div class="stat-label" data-key="stat-roles">Foydalanuvchi Rollari</div>
                </div>
                <div class="stat floating" style="animation-delay: 0.6s;">
                    <div class="stat-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-number">3</div>
                    <div class="stat-label" data-key="stat-languages">Tillar</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="loyiha" class="about">
        <div class="container">
            <div class="section-title">
                <h2 data-key="about-title">📋 Loyiha Haqida</h2>
                <p data-key="about-subtitle">
                    DOM Product Project - zamonaviy elektron tijorat ehtiyojlari uchun ishlab chiqilgan to'liq yechim
                </p>
            </div>

            <div class="about-content">
                <div class="about-grid">
                    <div class="about-card mission">
                        <div class="about-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 data-key="about-mission">Missiya</h3>
                        <p data-key="about-mission-desc">Kichik va o'rta bizneslar uchun professional darajada elektron tijorat platformasini qulay va arzon narxda taqdim etish</p>
                    </div>

                    <div class="about-card vision">
                        <div class="about-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 data-key="about-vision">Vizyon</h3>
                        <p data-key="about-vision-desc">O'zbekistonda elektron tijorat sohasida yetakchi texnologik yechimlar taqdim etuvchi platforma bo'lish</p>
                    </div>

                    <div class="about-card goal">
                        <div class="about-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 data-key="about-goal">Maqsad</h3>
                        <p data-key="about-goal-desc">Har qanday hajmdagi biznes uchun qulay, xavfsiz va samarali onlayn savdo platformasini yaratish</p>
                    </div>
                </div>

                <div class="about-details">
                    <div class="detail-section">
                        <h3 data-key="about-what-title">Bu loyiha nima?</h3>
                        <p data-key="about-what-desc">DOM Product Project - bu to'liq funktsional elektron tijorat platformasi bo'lib, mahsulotlarni sotish, mijozlarni boshqarish, to'lovlarni qayta ishlash va analitika uchun barcha zaruiy vositalarni o'z ichiga oladi. Platform mikroxizmat arxitekturasi asosida qurilgan va zamonaviy veb texnologiyalardan foydalanadi.</p>
                    </div>

                    <div class="detail-section">
                        <h3 data-key="about-why-title">Nega aynan bu platforma?</h3>
                        <ul class="about-features">
                            <li data-key="about-feature-1"><i class="fas fa-check-circle"></i> To'liq bepul va ochiq kod</li>
                            <li data-key="about-feature-2"><i class="fas fa-check-circle"></i> O'zbek, Rus va Ingliz tillarida</li>
                            <li data-key="about-feature-3"><i class="fas fa-check-circle"></i> Mobile va desktop uchun optimallashtirilgan</li>
                            <li data-key="about-feature-4"><i class="fas fa-check-circle"></i> Zamonaviy xavfsizlik standartlari</li>
                            <li data-key="about-feature-5"><i class="fas fa-check-circle"></i> Osongina o'rnatish va sozlash</li>
                            <li data-key="about-feature-6"><i class="fas fa-check-circle"></i> Kengaytirilishi mumkin arxitektura</li>
                        </ul>
                    </div>

                    <div class="detail-section">
                        <h3 data-key="about-tech-stack">Texnologik stek</h3>
                        <p data-key="about-tech-desc">Platform eng so'nggi va ishonchli texnologiyalar asosida qurilgan. Frontend qismi React 19.1.0 bilan, backend qismi Laravel 8.75 bilan ishlab chiqilgan. Ma'lumotlar ombori sifatida MariaDB va kesh uchun Redis ishlatiladi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="imkoniyatlar" class="features">
        <div class="container">
            <div class="section-title">
                <h2 data-key="features-title">⚡ Asosiy Imkoniyatlar</h2>
                <p data-key="features-subtitle">
                    Zamonaviy e-commerce platformasi uchun zarur bo'lgan barcha funksiyalar
                </p>
            </div>
            <div class="features-grid">
                <div class="feature fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 data-key="feature-security">Yuqori Xavfsizlik</h3>
                    <p data-key="feature-security-desc">Laravel Sanctum, RBAC, va zamonaviy xavfsizlik choralari</p>
                </div>
                <div class="feature fade-in-up" style="animation-delay: 0.1s;">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 data-key="feature-mobile">Mobile-First</h3>
                    <p data-key="feature-mobile-desc">Responsive dizayn va alohida Android ilovasi</p>
                </div>
                <div class="feature fade-in-up" style="animation-delay: 0.2s;">
                    <div class="feature-icon">
                        <i class="fas fa-language"></i>
                    </div>
                    <h3 data-key="feature-multilang">Ko'p Tillilik</h3>
                    <p data-key="feature-multilang-desc">O'zbek, Rus va Ingliz tillarida to'liq qo'llab-quvvatlash</p>
                </div>
                <div class="feature fade-in-up" style="animation-delay: 0.3s;">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 data-key="feature-performance">Yuqori Unumdorlik</h3>
                    <p data-key="feature-performance-desc">Optimizatsiya qilingan kod va kesh tizimi</p>
                </div>
                <div class="feature fade-in-up" style="animation-delay: 0.4s;">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 data-key="feature-microservice">Mikroxizmat</h3>
                    <p data-key="feature-microservice-desc">Alohida frontend va backend arxitekturasi</p>
                </div>
                <div class="feature fade-in-up" style="animation-delay: 0.5s;">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 data-key="feature-analytics">Analitika</h3>
                    <p data-key="feature-analytics-desc">To'liq statistika va hisobotlar tizimi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section id="texnologiyalar" class="tech-stack">
        <div class="container">
            <div class="section-title">
                <h2 data-key="tech-title">🛠️ Texnologiyalar</h2>
                <p data-key="tech-subtitle">
                    Zamonaviy va ishonchli texnologiyalar asosida qurilgan
                </p>
            </div>
            <div class="tech-grid">
                <div class="tech-card frontend">
                    <div class="tech-icon">
                        <i class="fab fa-react"></i>
                    </div>
                    <h3>Frontend</h3>
                    <p data-key="tech-frontend">React 19.1.0 + Vite + Framer Motion</p>
                </div>
                <div class="tech-card backend">
                    <div class="tech-icon">
                        <i class="fab fa-laravel"></i>
                    </div>
                    <h3>Backend</h3>
                    <p data-key="tech-backend">Laravel 8.75 + PHP 8.2 + Sanctum</p>
                </div>
                <div class="tech-card database">
                    <div class="tech-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Database</h3>
                    <p data-key="tech-database">MariaDB + Redis Cache</p>
                </div>
                <div class="tech-card mobile">
                    <div class="tech-icon">
                        <i class="fab fa-android"></i>
                    </div>
                    <h3>Mobile</h3>
                    <p data-key="tech-mobile">Android Native + Kotlin/Java</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Architecture -->
    <section id="arxitektura" class="architecture">
        <div class="container">
            <div class="section-title">
                <h2 data-key="arch-title">🏗️ Arxitektura</h2>
                <p data-key="arch-subtitle">
                    Mikroxizmat arxitekturasi va zamonaviy DevOps yondashuvi
                </p>
            </div>
            <div class="architecture-diagram">
                <div class="diagram-flow">
                    <div class="diagram-box">
                        <i class="fab fa-react" style="color: #61dafb; font-size: 2rem;"></i>
                        <div style="margin-top: 0.5rem; font-weight: 600;">Frontend</div>
                        <div style="font-size: 0.75rem; color: var(--gray-600);">React</div>
                    </div>
                    <div class="diagram-arrow">→</div>
                    <div class="diagram-box">
                        <i class="fas fa-server" style="color: var(--primary-color); font-size: 2rem;"></i>
                        <div style="margin-top: 0.5rem; font-weight: 600;">Nginx</div>
                        <div style="font-size: 0.75rem; color: var(--gray-600);">Load Balancer</div>
                    </div>
                    <div class="diagram-arrow">→</div>
                    <div class="diagram-box">
                        <i class="fab fa-laravel" style="color: #ff2d20; font-size: 2rem;"></i>
                        <div style="margin-top: 0.5rem; font-weight: 600;">Backend</div>
                        <div style="font-size: 0.75rem; color: var(--gray-600);">Laravel API</div>
                    </div>
                    <div class="diagram-arrow">→</div>
                    <div class="diagram-box">
                        <i class="fas fa-database" style="color: #336791; font-size: 2rem;"></i>
                        <div style="margin-top: 0.5rem; font-weight: 600;">Database</div>
                        <div style="font-size: 0.75rem; color: var(--gray-600);">MariaDB</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Start -->
    <section id="boshlash" class="quick-start">
        <div class="container">
            <div class="section-title">
                <h2 data-key="quickstart-title">🚀 Tezkor Boshlash</h2>
                <p data-key="quickstart-subtitle">
                    Loyihani bir necha daqiqada ishga tushiring
                </p>
            </div>

            <div class="command-box">
                <button class="copy-btn" onclick="copyToClipboard('git clone https://github.com/urinboy/dom-product-project.git\ncd dom-product-project')">
                    <i class="fas fa-copy"></i>
                </button>
                <div class="command">
                    git clone https://github.com/urinboy/dom-product-project.git<br>
                    cd dom-product-project
                </div>
            </div>

            <div class="command-box">
                <button class="copy-btn" onclick="copyToClipboard('docker-compose up -d')">
                    <i class="fas fa-copy"></i>
                </button>
                <div class="command">
                    docker-compose up -d
                </div>
            </div>

            <div class="command-box">
                <button class="copy-btn" onclick="copyToClipboard('docker exec -it dom_product_php82 bash\ncomposer install\nphp artisan migrate\nphp artisan db:seed')">
                    <i class="fas fa-copy"></i>
                </button>
                <div class="command">
                    docker exec -it dom_product_php82 bash<br>
                    composer install<br>
                    php artisan migrate<br>
                    php artisan db:seed
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <p style="color: var(--gray-300); margin-bottom: 1rem;" data-key="access-info">
                    Loyihaga kirish:
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="http://localhost:3000" class="btn btn-primary" target="_blank">
                        <i class="fas fa-globe"></i>
                        Frontend (Port 3000)
                    </a>
                    <a href="http://localhost:8000" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-cog"></i>
                        API (Port 8000)
                    </a>
                    <a href="http://localhost:8080" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-database"></i>
                        phpMyAdmin (Port 8080)
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Page Up Button -->
    <button class="page-up" id="pageUpBtn" onclick="scrollToTop()" title="Back to Top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="https://github.com/urinboy/dom-product-project" target="_blank" data-key="github">GitHub Repository</a>
                <a href="https://github.com/urinboy/dom-product-project/issues" target="_blank" data-key="issues">Issues</a>
                <a href="#" data-key="documentation">Dokumentatsiya</a>
                <a href="/docs" data-key="api-docs">API Docs</a>
                <a href="#" data-key="support">Yordam</a>
            </div>

            <div class="social-links">
                <a href="https://github.com/urinboy" target="_blank">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fab fa-telegram"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>

            <p style="margin-top: 2rem; color: var(--gray-500);">
                © 2025 DOM Product Project. MIT License.
                <br>
                <span data-key="footer-desc">Zamonaviy e-commerce platformasi</span>
            </p>
        </div>
    </footer>

    <script>
        // Theme Management
        let currentTheme = localStorage.getItem('theme') || null;

        function initTheme() {
            const root = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');

            // Detect system preference on first visit
            if (!localStorage.getItem('theme')) {
                const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                currentTheme = systemDark ? 'dark' : 'light';
                localStorage.setItem('theme', currentTheme);
            }

            root.setAttribute('data-theme', currentTheme);
            themeIcon.textContent = currentTheme === 'dark' ? '☀️' : '🌙';
        }

        function toggleTheme() {
            const themes = ['light', 'dark'];
            const icons = ['🌙', '☀️'];

            const currentIndex = themes.indexOf(currentTheme);
            const nextIndex = (currentIndex + 1) % themes.length;

            currentTheme = themes[nextIndex];
            localStorage.setItem('theme', currentTheme);

            document.getElementById('theme-icon').textContent = icons[nextIndex];
            initTheme();
        }

        // Mobile Navigation
        function toggleNav() {
            const navLinks = document.getElementById('nav-links');
            const navToggle = document.querySelector('.nav-toggle');

            navLinks.classList.toggle('active');
            navToggle.classList.toggle('active');
        }

        // Close mobile nav when clicking on a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                const navLinks = document.getElementById('nav-links');
                const navToggle = document.querySelector('.nav-toggle');
                navLinks.classList.remove('active');
                navToggle.classList.remove('active');
            });
        });

        // Language switching functionality
        const translations = {
            uz: {
                'about': 'Loyiha Haqida',
                'features': 'Imkoniyatlar',
                'technologies': 'Texnologiyalar',
                'architecture': 'Arxitektura',
                'quickstart': 'Boshlash',
                'version': 'Version 1.3.5 - Professional',
                'hero-title': '🛒 DOM Product Project',
                'hero-subtitle': 'Zamonaviy texnologiyalar asosida qurilgan professional elektron tijorat platformasi',
                'get-started': 'Boshlash',
                'view-github': 'GitHub\'da Ko\'rish',
                'stat-platforms': 'Platformalar',
                'stat-models': 'Ma\'lumot Modellari',
                'stat-roles': 'Foydalanuvchi Rollari',
                'stat-languages': 'Tillar',

                // About section
                'about-title': '📋 Loyiha Haqida',
                'about-subtitle': 'DOM Product Project - zamonaviy elektron tijorat ehtiyojlari uchun ishlab chiqilgan to\'liq yechim',
                'about-mission': 'Missiya',
                'about-mission-desc': 'Kichik va o\'rta bizneslar uchun professional darajada elektron tijorat platformasini qulay va arzon narxda taqdim etish',
                'about-vision': 'Vizyon',
                'about-vision-desc': 'O\'zbekistonda elektron tijorat sohasida yetakchi texnologik yechimlar taqdim etuvchi platforma bo\'lish',
                'about-goal': 'Maqsad',
                'about-goal-desc': 'Har qanday hajmdagi biznes uchun qulay, xavfsiz va samarali onlayn savdo platformasini yaratish',
                'about-what-title': 'Bu loyiha nima?',
                'about-what-desc': 'DOM Product Project - bu to\'liq funktsional elektron tijorat platformasi bo\'lib, mahsulotlarni sotish, mijozlarni boshqarish, to\'lovlarni qayta ishlash va analitika uchun barcha zaruiy vositalarni o\'z ichiga oladi. Platform mikroxizmat arxitekturasi asosida qurilgan va zamonaviy veb texnologiyalardan foydalanadi.',
                'about-why-title': 'Nega aynan bu platforma?',
                'about-feature-1': 'To\'liq bepul va ochiq kod',
                'about-feature-2': 'O\'zbek, Rus va Ingliz tillarida',
                'about-feature-3': 'Mobile va desktop uchun optimallashtirilgan',
                'about-feature-4': 'Zamonaviy xavfsizlik standartlari',
                'about-feature-5': 'Osongina o\'rnatish va sozlash',
                'about-feature-6': 'Kengaytirilishi mumkin arxitektura',
                'about-tech-stack': 'Texnologik stek',
                'about-tech-desc': 'Platform eng so\'nggi va ishonchli texnologiyalar asosida qurilgan. Frontend qismi React 19.1.0 bilan, backend qismi Laravel 8.75 bilan ishlab chiqilgan. Ma\'lumotlar ombori sifatida MariaDB va kesh uchun Redis ishlatiladi.',

                'features-title': '⚡ Asosiy Imkoniyatlar',
                'features-subtitle': 'Zamonaviy e-commerce platformasi uchun zarur bo\'lgan barcha funksiyalar',
                'feature-security': 'Yuqori Xavfsizlik',
                'feature-security-desc': 'Laravel Sanctum, RBAC, va zamonaviy xavfsizlik choralari',
                'feature-mobile': 'Mobile-First',
                'feature-mobile-desc': 'Responsive dizayn va alohida Android ilovasi',
                'feature-multilang': 'Ko\'p Tillilik',
                'feature-multilang-desc': 'O\'zbek, Rus va Ingliz tillarida to\'liq qo\'llab-quvvatlash',
                'feature-performance': 'Yuqori Unumdorlik',
                'feature-performance-desc': 'Optimizatsiya qilingan kod va kesh tizimi',
                'feature-microservice': 'Mikroxizmat',
                'feature-microservice-desc': 'Alohida frontend va backend arxitekturasi',
                'feature-analytics': 'Analitika',
                'feature-analytics-desc': 'To\'liq statistika va hisobotlar tizimi',
                'tech-title': '🛠️ Texnologiyalar',
                'tech-subtitle': 'Zamonaviy va ishonchli texnologiyalar asosida qurilgan',
                'tech-frontend': 'React 19.1.0 + Vite + Framer Motion',
                'tech-backend': 'Laravel 8.75 + PHP 8.2 + Sanctum',
                'tech-database': 'MariaDB + Redis Cache',
                'tech-mobile': 'Android Native + Kotlin/Java',
                'arch-title': '🏗️ Arxitektura',
                'arch-subtitle': 'Mikroxizmat arxitekturasi va zamonaviy DevOps yondashuvi',
                'quickstart-title': '🚀 Tezkor Boshlash',
                'quickstart-subtitle': 'Loyihani bir necha daqiqada ishga tushiring',
                'access-info': 'Loyihaga kirish:',
                'github': 'GitHub Repository',
                'issues': 'Issues',
                'documentation': 'Dokumentatsiya',
                'api-docs': 'API Docs',
                'support': 'Yordam',
                'footer-desc': 'Zamonaviy e-commerce platformasi'
            },
            en: {
                'about': 'About',
                'features': 'Features',
                'technologies': 'Technologies',
                'architecture': 'Architecture',
                'quickstart': 'Quick Start',
                'version': 'Version 1.3.5 - Professional',
                'hero-title': '🛒 DOM Product Project',
                'hero-subtitle': 'Professional e-commerce platform built with modern technologies',
                'get-started': 'Get Started',
                'view-github': 'View on GitHub',
                'stat-platforms': 'Platforms',
                'stat-models': 'Data Models',
                'stat-roles': 'User Roles',
                'stat-languages': 'Languages',

                // About section
                'about-title': '📋 About Project',
                'about-subtitle': 'DOM Product Project - a complete solution developed for modern e-commerce needs',
                'about-mission': 'Mission',
                'about-mission-desc': 'To provide professional-grade e-commerce platform for small and medium businesses at affordable prices',
                'about-vision': 'Vision',
                'about-vision-desc': 'To become a leading technology solutions provider in the e-commerce sector in Uzbekistan',
                'about-goal': 'Goal',
                'about-goal-desc': 'To create a convenient, secure and efficient online trading platform for businesses of any size',
                'about-what-title': 'What is this project?',
                'about-what-desc': 'DOM Product Project is a fully functional e-commerce platform that includes all the necessary tools for selling products, managing customers, processing payments and analytics. The platform is built on microservice architecture and uses modern web technologies.',
                'about-why-title': 'Why this platform?',
                'about-feature-1': 'Completely free and open source',
                'about-feature-2': 'Available in Uzbek, Russian and English',
                'about-feature-3': 'Optimized for mobile and desktop',
                'about-feature-4': 'Modern security standards',
                'about-feature-5': 'Easy installation and setup',
                'about-feature-6': 'Scalable architecture',
                'about-tech-stack': 'Technology Stack',
                'about-tech-desc': 'The platform is built on the latest and most reliable technologies. The frontend is developed with React 19.1.0, the backend with Laravel 8.75. MariaDB is used as the database and Redis for caching.',

                'features-title': '⚡ Key Features',
                'features-subtitle': 'All necessary functions for modern e-commerce platform',
                'feature-security': 'High Security',
                'feature-security-desc': 'Laravel Sanctum, RBAC, and modern security measures',
                'feature-mobile': 'Mobile-First',
                'feature-mobile-desc': 'Responsive design and dedicated Android app',
                'feature-multilang': 'Multilingual',
                'feature-multilang-desc': 'Full support in Uzbek, Russian and English',
                'feature-performance': 'High Performance',
                'feature-performance-desc': 'Optimized code and caching system',
                'feature-microservice': 'Microservice',
                'feature-microservice-desc': 'Separate frontend and backend architecture',
                'feature-analytics': 'Analytics',
                'feature-analytics-desc': 'Complete statistics and reporting system',
                'tech-title': '🛠️ Technologies',
                'tech-subtitle': 'Built on modern and reliable technologies',
                'tech-frontend': 'React 19.1.0 + Vite + Framer Motion',
                'tech-backend': 'Laravel 8.75 + PHP 8.2 + Sanctum',
                'tech-database': 'MariaDB + Redis Cache',
                'tech-mobile': 'Android Native + Kotlin/Java',
                'arch-title': '🏗️ Architecture',
                'arch-subtitle': 'Microservice architecture and modern DevOps approach',
                'quickstart-title': '🚀 Quick Start',
                'quickstart-subtitle': 'Get the project running in minutes',
                'access-info': 'Access the project:',
                'github': 'GitHub Repository',
                'issues': 'Issues',
                'documentation': 'Documentation',
                'api-docs': 'API Docs',
                'support': 'Support',
                'footer-desc': 'Modern e-commerce platform'
            },
            ru: {
                'about': 'О проекте',
                'features': 'Возможности',
                'technologies': 'Технологии',
                'architecture': 'Архитектура',
                'quickstart': 'Быстрый старт',
                'version': 'Версия 1.3.5 - Профессиональная',
                'hero-title': '🛒 DOM Product Project',
                'hero-subtitle': 'Профессиональная платформа электронной коммерции на современных технологиях',
                'get-started': 'Начать',
                'view-github': 'Посмотреть на GitHub',
                'stat-platforms': 'Платформы',
                'stat-models': 'Модели данных',
                'stat-roles': 'Роли пользователей',
                'stat-languages': 'Языки',

                // About section
                'about-title': '📋 О проекте',
                'about-subtitle': 'DOM Product Project - полное решение, разработанное для современных потребностей электронной коммерции',
                'about-mission': 'Миссия',
                'about-mission-desc': 'Предоставить платформу электронной коммерции профессионального уровня для малого и среднего бизнеса по доступным ценам',
                'about-vision': 'Видение',
                'about-vision-desc': 'Стать ведущим поставщиком технологических решений в сфере электронной коммерции в Узбекистане',
                'about-goal': 'Цель',
                'about-goal-desc': 'Создать удобную, безопасную и эффективную онлайн-торговую платформу для бизнеса любого размера',
                'about-what-title': 'Что это за проект?',
                'about-what-desc': 'DOM Product Project - это полнофункциональная платформа электронной коммерции, которая включает в себя все необходимые инструменты для продажи товаров, управления клиентами, обработки платежей и аналитики. Платформа построена на микросервисной архитектуре и использует современные веб-технологии.',
                'about-why-title': 'Почему именно эта платформа?',
                'about-feature-1': 'Полностью бесплатная и с открытым исходным кодом',
                'about-feature-2': 'Доступна на узбекском, русском и английском языках',
                'about-feature-3': 'Оптимизирована для мобильных устройств и компьютеров',
                'about-feature-4': 'Современные стандарты безопасности',
                'about-feature-5': 'Простая установка и настройка',
                'about-feature-6': 'Масштабируемая архитектура',
                'about-tech-stack': 'Технологический стек',
                'about-tech-desc': 'Платформа построена на новейших и надежных технологиях. Frontend разработан с помощью React 19.1.0, backend - с Laravel 8.75. В качестве базы данных используется MariaDB, а для кеширования - Redis.',

                'features-title': '⚡ Основные возможности',
                'features-subtitle': 'Все необходимые функции для современной платформы электронной коммерции',
                'feature-security': 'Высокая безопасность',
                'feature-security-desc': 'Laravel Sanctum, RBAC и современные меры безопасности',
                'feature-mobile': 'Mobile-First',
                'feature-mobile-desc': 'Адаптивный дизайн и отдельное приложение для Android',
                'feature-multilang': 'Многоязычность',
                'feature-multilang-desc': 'Полная поддержка узбекского, русского и английского языков',
                'feature-performance': 'Высокая производительность',
                'feature-performance-desc': 'Оптимизированный код и система кеширования',
                'feature-microservice': 'Микросервис',
                'feature-microservice-desc': 'Отдельная архитектура frontend и backend',
                'feature-analytics': 'Аналитика',
                'feature-analytics-desc': 'Полная система статистики и отчетов',
                'tech-title': '🛠️ Технологии',
                'tech-subtitle': 'Построено на современных и надежных технологиях',
                'tech-frontend': 'React 19.1.0 + Vite + Framer Motion',
                'tech-backend': 'Laravel 8.75 + PHP 8.2 + Sanctum',
                'tech-database': 'MariaDB + Redis Cache',
                'tech-mobile': 'Android Native + Kotlin/Java',
                'arch-title': '🏗️ Архитектура',
                'arch-subtitle': 'Архитектура микросервисов и современный подход DevOps',
                'quickstart-title': '🚀 Быстрый старт',
                'quickstart-subtitle': 'Запустите проект за несколько минут',
                'access-info': 'Доступ к проекту:',
                'github': 'GitHub Repository',
                'issues': 'Issues',
                'documentation': 'Документация',
                'api-docs': 'API Docs',
                'support': 'Поддержка',
                'footer-desc': 'Современная платформа электронной коммерции'
            }
        };

        let currentLang = localStorage.getItem('language') || 'uz';

        function toggleLanguage() {
            const languages = ['uz', 'en', 'ru'];
            const flags = ['🇺🇿', '🇺🇸', '🇷🇺'];

            const currentIndex = languages.indexOf(currentLang);
            const nextIndex = (currentIndex + 1) % languages.length;

            currentLang = languages[nextIndex];
            localStorage.setItem('language', currentLang);

            document.getElementById('flag').textContent = flags[nextIndex];

            updateTexts();

            // Smooth scroll to top of page
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateTexts() {
            const elements = document.querySelectorAll('[data-key]');
            elements.forEach(element => {
                const key = element.getAttribute('data-key');
                if (translations[currentLang] && translations[currentLang][key]) {
                    element.textContent = translations[currentLang][key];
                }
            });
        }

        function initLanguage() {
            const languages = ['uz', 'en', 'ru'];
            const flags = ['🇺🇿', '🇺🇸', '🇷🇺'];

            const index = languages.indexOf(currentLang);
            document.getElementById('flag').textContent = flags[index];
            updateTexts();
        }

        // Page Up functionality
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show/hide page up button based on scroll position
        function togglePageUpButton() {
            const pageUpBtn = document.getElementById('pageUpBtn');
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 300) {
                pageUpBtn.classList.add('visible');
            } else {
                pageUpBtn.classList.remove('visible');
            }
        }

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show feedback
                const btn = event.target.closest('.copy-btn');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.background = 'var(--success-color)';

                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.style.background = 'var(--primary-color)';
                }, 1000);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                const btn = event.target.closest('.copy-btn');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.background = 'var(--success-color)';

                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.style.background = 'var(--primary-color)';
                }, 1000);
            });
        }

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight - 20;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up, .floating').forEach(el => {
            observer.observe(el);
        });

        // Handle window resize for mobile optimization
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                // Close mobile nav if window is resized to desktop
                if (window.innerWidth > 768) {
                    const navLinks = document.getElementById('nav-links');
                    const navToggle = document.querySelector('.nav-toggle');
                    navLinks.classList.remove('active');
                    navToggle.classList.remove('active');
                }
            }, 250);
        });

        // Prevent body scroll when mobile menu is open
        const navToggle = document.querySelector('.nav-toggle');
        navToggle.addEventListener('click', function() {
            const navLinks = document.getElementById('nav-links');
            if (navLinks.classList.contains('active')) {
                document.body.style.overflow = '';
            } else {
                document.body.style.overflow = 'hidden';
            }
        });

        // Page Up button scroll listener
        window.addEventListener('scroll', togglePageUpButton);

        // Initialize page up button on load
        togglePageUpButton();

        // Initialize
        initTheme();
        initLanguage();
    </script>
</body>
</html>

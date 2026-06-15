<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/tracker.php'; // Logs unique visitors silently

// 1. Fetch Website SEO & Metadata Settings
try {
    $settings = $pdo->query("SELECT * FROM website_settings LIMIT 1")->fetch();
} catch (Exception $e) {
    $settings = false;
}

if (!$settings) {
    $settings = [
        'meta_title' => 'Olusegun Global Solutions Ltd | Shopify Expert & E-commerce Agency',
        'meta_description' => 'Official Shopify Partner & Premium Digital Agency. We specialize in custom Shopify Store Development, Redesigns, SEO Optimization, Speed, Branding, and Global E-commerce Growth.',
        'meta_keywords' => 'Shopify Expert, Shopify Partner, Shopify Development, Shopify Store Design, Shopify SEO, E-commerce Development, Social Media Marketing, Website Design, UI UX Design, Mobile App Development, Graphic Design Services, Branding Agency, Digital Marketing Agency, Olusegun Global Solutions Ltd',
        'og_title' => 'Olusegun Global Solutions Ltd | Shopify Expert & E-commerce Agency',
        'og_description' => 'Transform your business with our Shopify Store Development, SEO optimization, and premium digital agency solutions. Reach global markets.',
        'favicon_path' => 'assets/favicon.png',
        'contact_email' => 'olusegunintegratedsolution@gmail.com',
        'whatsapp_number' => '12052371919',
        'telegram_username' => 'Fasinufelix',
        'footer_copyright' => '&copy; 2026 OLUSEGUN GLOBAL SOLUTIONS LTD. All Rights Reserved.'
    ];
}

// 2. Fetch Services from Database
try {
    $db_services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) {
    $db_services = [];
}

// 3. Fetch Blogs from Database
try {
    $db_blogs = $pdo->query("SELECT b.*, c.name as category_name FROM blogs b JOIN blog_categories c ON b.category_id = c.id WHERE b.status = 'published' ORDER BY b.created_at DESC")->fetchAll();
} catch (Exception $e) {
    $db_blogs = [];
}

// 4. Fetch Testimonials from Database
try {
    $db_testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $db_testimonials = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic SEO Meta Tags -->
    <title><?= sanitize_output($settings['meta_title']) ?></title>
    <meta name="description" content="<?= sanitize_output($settings['meta_description']) ?>">
    <meta name="keywords" content="<?= sanitize_output($settings['meta_keywords']) ?>">
    <meta name="author" content="Olusegun Global Solutions Ltd">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= sanitize_output($settings['meta_title']) ?>">
    <meta property="og:description" content="<?= sanitize_output($settings['meta_description']) ?>">
    <meta property="og:image" content="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&h=630&q=80">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#10b981', // Emerald/Shopify green
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        },
                        dark: {
                            800: '#0f172a',
                            900: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Premium CSS Layout Classes -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }
        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-green {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.15);
        }
        .glow-hover:hover {
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.3);
            border-color: rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 w-full z-50 glass border-b border-slate-800 transition-all duration-300" id="mainHeader">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#home" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-lg bg-brand-500 flex items-center justify-center glow-green transition-transform duration-300 group-hover:scale-105">
                    <i class="fa-brands fa-shopify text-white text-xl"></i>
                </div>
                <div>
                    <span class="font-extrabold text-lg tracking-tight block text-white group-hover:text-brand-500 transition-colors">OLUSEGUN</span>
                    <span class="text-xs text-slate-400 block -mt-1 tracking-widest font-semibold uppercase">GLOBAL SOLUTIONS</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="#home" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">Home</a>
                <a href="#about" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">About</a>
                <a href="#services" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">Services</a>
                <a href="#shopify-expert" class="text-sm font-medium text-brand-500 hover:text-brand-600 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Shopify Partner
                </a>
                <a href="#audits" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">Audits</a>
                <a href="#case-studies" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">Case Studies</a>
                <a href="#blog" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">Insights</a>
                <a href="#faq" class="text-sm font-medium text-slate-300 hover:text-brand-500 transition-colors">FAQ</a>
            </nav>

            <div class="hidden lg:flex items-center space-x-4">
                <a href="#contact" class="px-5 py-2.5 rounded-lg bg-brand-500 hover:bg-brand-600 text-white font-medium text-sm transition-all duration-300 hover:-translate-y-0.5 shadow-lg shadow-brand-500/10">
                    Get Free Consult
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button class="lg:hidden text-slate-300 hover:text-white focus:outline-none" id="mobileMenuBtn" aria-label="Toggle Navigation Menu">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Drawer Menu -->
        <div class="hidden lg:hidden glass border-t border-slate-800 absolute top-20 left-0 w-full p-6 space-y-4" id="mobileMenu">
            <a href="#home" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Home</a>
            <a href="#about" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">About</a>
            <a href="#services" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Services</a>
            <a href="#shopify-expert" class="block text-brand-500 hover:text-brand-600 font-medium py-2" onclick="toggleMenu()">Shopify Partner Services</a>
            <a href="#audits" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Audits</a>
            <a href="#case-studies" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Case Studies</a>
            <a href="#blog" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Insights/Blog</a>
            <a href="#faq" class="block text-slate-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">FAQ</a>
            <a href="#contact" class="block text-center bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-lg" onclick="toggleMenu()">Get Free Consult</a>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="home" class="relative pt-32 pb-24 md:pt-44 md:pb-36 bg-gradient-to-b from-[#0b132b] to-[#020617] overflow-hidden">
        <div class="absolute top-1/4 left-1/10 w-96 h-96 bg-brand-500/10 rounded-full filter blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-10 right-1/10 w-96 h-96 bg-indigo-500/15 rounded-full filter blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center space-x-2 bg-slate-900/80 border border-slate-700/60 px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wide text-brand-500">
                        <i class="fa-brands fa-shopify"></i>
                        <span>VERIFIED SHOPIFY PARTNER</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-none">
                        Scale Your Store Globally with <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-teal-400">Shopify Experts</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-slate-300 max-w-xl">
                        Olusegun Global Solutions Ltd designs, builds, and optimizes ultra-premium, high-converting digital storefronts. Let’s turn your traffic into enterprise-level sales.
                    </p>

                    <div class="pt-2 grid grid-cols-3 gap-4 border-t border-slate-800 max-w-lg">
                        <div>
                            <span class="block text-2xl font-bold text-white">150+</span>
                            <span class="text-xs text-slate-400">Stores Built</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-white">99%</span>
                            <span class="text-xs text-slate-400">Success Rate</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-white">$10M+</span>
                            <span class="text-xs text-slate-400">Client Revenue</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#contact" class="px-8 py-4 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg text-center transition-all duration-300 hover:shadow-lg hover:shadow-brand-500/25">
                            Launch Your Project
                        </a>
                        <a href="#services" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-lg text-center border border-slate-700 hover:border-slate-600 transition-all duration-300">
                            Explore Services
                        </a>
                    </div>
                </div>

                <!-- Right Visual Area -->
                <div class="lg:col-span-5 relative mt-8 lg:mt-0">
                    <div class="relative mx-auto max-w-[450px] lg:max-w-none">
                        <div class="glass rounded-2xl p-6 glow-green relative overflow-hidden border border-slate-700/50">
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                </div>
                                <span class="text-xs font-semibold text-brand-500 tracking-wider">OLUSEGUN DASHBOARD</span>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-slate-900/90 rounded-xl p-4 border border-slate-800">
                                    <span class="text-xs text-slate-400 block">Total Revenue Managed</span>
                                    <div class="flex items-baseline space-x-2 mt-1">
                                        <span class="text-3xl font-extrabold text-white">$4,821,304</span>
                                        <span class="text-xs text-emerald-400 font-semibold flex items-center gap-0.5">
                                            <i class="fa-solid fa-arrow-trend-up"></i> +42.1%
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-slate-900/90 rounded-xl p-3 border border-slate-800">
                                        <span class="text-xs text-slate-400 block">Conversion Rate</span>
                                        <span class="text-lg font-bold text-white mt-1 block">4.82%</span>
                                    </div>
                                    <div class="bg-slate-900/90 rounded-xl p-3 border border-slate-800">
                                        <span class="text-xs text-slate-400 block">Speed Score</span>
                                        <span class="text-lg font-bold text-brand-500 mt-1 block">98/100</span>
                                    </div>
                                </div>

                                <div class="bg-brand-500/5 rounded-xl p-4 border border-brand-500/20 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-brands fa-shopify text-3xl text-brand-500"></i>
                                        <div>
                                            <h4 class="text-sm font-bold text-white">Shopify Partner Program</h4>
                                            <p class="text-[11px] text-slate-400">Officially Recognized Agency</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-certificate text-brand-500 text-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ABOUT US SECTION -->
    <section id="about" class="py-24 bg-slate-950 border-t border-slate-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Our Identity</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Who is Olusegun Global Solutions?</h2>
                <p class="text-slate-400 mt-4">We are an elite team of e-commerce specialists, engineers, and digital marketers focused on building flawless digital platforms for growing brands.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" alt="Olusegun Agency Team" class="w-full h-[400px] object-cover rounded-2xl brightness-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-2xl font-bold text-white">Empowering International E-commerce Scaling</h3>
                    <p class="text-slate-300">
                        At Olusegun Global Solutions Ltd, we combine highly specialized technical skillsets with cutting-edge design methodologies. As official Shopify Partners, we eliminate the complex technical blockages that prevent business growth.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500 mt-1">
                                <i class="fa-solid fa-bullseye text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Our Mission</h4>
                                <p class="text-sm text-slate-400">To build highly optimized, secure, and beautiful digital systems that unlock repeatable global revenue for our clients.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500 mt-1">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Our Vision</h4>
                                <p class="text-sm text-slate-400">To remain a top-tier global authority in Shopify Ecosystem Optimization and strategic e-commerce consulting.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500 mt-1">
                                <i class="fa-solid fa-handshake text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Our Values</h4>
                                <p class="text-sm text-slate-400">Absolute transparency, speed, data-driven decisions, and relentless client-centric alignment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION (With Dynamic Database Loop & Static Fallback) -->
    <section id="services" class="py-24 bg-slate-900/50 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Core Services</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Specialized Digital Capabilities</h2>
                <p class="text-slate-400 mt-4">We cover the entire lifecycle of your digital assets. Browse our professional service offerings below.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="servicesGrid">
                <!-- Services will load from PHP if available; otherwise falls back to beautiful JS array definitions to prevent page breakage -->
            </div>
        </div>
    </section>

    <!-- SHOPIFY EXPERT / PARTNER DEDICATED SECTION -->
    <section id="shopify-expert" class="py-24 bg-gradient-to-b from-slate-950 to-[#0c1815] border-t border-slate-900 relative">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,rgba(16,185,129,0.08),transparent_50%)]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 mb-16 border-b border-emerald-900/30 pb-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center space-x-2 bg-emerald-950 border border-emerald-800 px-3 py-1 rounded-full text-xs font-semibold text-brand-500 mb-4">
                        <i class="fa-brands fa-shopify"></i>
                        <span>EXCLUSIVE SHOPIFY PARTNER ECOSYSTEM</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-extrabold text-white">Premium Shopify Partner Services</h2>
                    <p class="text-slate-300 mt-4 text-base sm:text-lg">
                        We don't just build sites; we craft complex e-commerce engines designed for conversions, customer retention, and massive structural scalability.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="bg-emerald-950/80 border border-brand-500/30 p-6 rounded-2xl flex items-center space-x-4 max-w-xs">
                        <i class="fa-brands fa-shopify text-5xl text-brand-500"></i>
                        <div>
                            <span class="text-xs font-bold text-slate-400 block uppercase">STATUS</span>
                            <span class="text-white font-extrabold text-lg">Verified Partner</span>
                            <span class="text-xs text-brand-500 block">✓ Custom App Approved</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="shopifyExpertGrid">
                <!-- Shopify specific modules loads here -->
            </div>
        </div>
    </section>

    <!-- STRATEGIC AUDITS (SEO & STORE ANALYSIS) -->
    <section id="audits" class="py-24 bg-slate-950 border-t border-slate-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Free Assessment</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Unlock Store Analysis Guides</h2>
                <p class="text-slate-400 mt-4">Submit your assets for a technical evaluation by our consulting team. No credit card required.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- SEO Audit Form -->
                <div class="glass p-8 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden" id="seoCard">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full filter blur-xl"></div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                            <i class="fa-solid fa-magnifying-glass-chart text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">SEO Analysis Request</h3>
                            <p class="text-xs text-slate-400">Search Engine Position Check</p>
                        </div>
                    </div>

                    <form id="seoAuditForm" class="space-y-4">
                        <input type="hidden" name="form_type" value="seo_audit">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Website URL *</label>
                            <input type="url" required name="url" placeholder="https://yourstore.com" class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Business Name *</label>
                            <input type="text" required name="business_name" placeholder="E-commerce Store Name" class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Industry *</label>
                                <input type="text" required name="industry" placeholder="Fashion, Tech, etc." class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Email Address *</label>
                                <input type="email" required name="email" placeholder="owner@store.com" class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg text-sm transition-all duration-300">
                            Submit Free SEO Audit Request
                        </button>
                    </form>
                    <div id="seoSuccess" class="hidden absolute inset-0 bg-slate-950/95 flex flex-col items-center justify-center p-6 text-center">
                        <i class="fa-regular fa-circle-check text-5xl text-brand-500 mb-4"></i>
                        <h4 class="text-xl font-bold text-white">SEO Request Submitted!</h4>
                        <p class="text-sm text-slate-400 mt-2 max-w-sm">We are analyzing your search metadata and will email you the full PDF report shortly.</p>
                        <button onclick="document.getElementById('seoSuccess').classList.add('hidden')" class="mt-6 px-4 py-2 bg-slate-800 rounded-lg text-xs text-white">Submit Another</button>
                    </div>
                </div>

                <!-- Store Analysis Form -->
                <div class="glass p-8 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden" id="storeCard">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full filter blur-xl"></div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-brand-500">
                            <i class="fa-solid fa-gauge-high text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Store Conversion Audit</h3>
                            <p class="text-xs text-slate-400">User Experience & Speed Check</p>
                        </div>
                    </div>

                    <form id="storeAuditForm" class="space-y-4">
                        <input type="hidden" name="form_type" value="store_audit">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Store URL *</label>
                            <input type="url" required name="store_url" placeholder="https://yourstore.myshopify.com" class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Platform Used *</label>
                                <select required name="platform" class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                                    <option value="Shopify">Shopify</option>
                                    <option value="WooCommerce">WooCommerce</option>
                                    <option value="Magento">Magento</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Monthly Revenue *</label>
                                <select required name="revenue" class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors">
                                    <option value="0-5k">$0 - $5k</option>
                                    <option value="5k-20k">$5k - $20k</option>
                                    <option value="20k-100k">$20k - $100k</option>
                                    <option value="100k+">$100k+</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Main Challenge *</label>
                            <textarea required name="challenge" rows="2" placeholder="Low conversion, poor loading speed, branding issues..." class="w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500 transition-colors"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg text-sm transition-all duration-300">
                            Submit Store conversion Audit
                        </button>
                    </form>
                    <div id="storeSuccess" class="hidden absolute inset-0 bg-slate-950/95 flex flex-col items-center justify-center p-6 text-center">
                        <i class="fa-regular fa-circle-check text-5xl text-brand-500 mb-4"></i>
                        <h4 class="text-xl font-bold text-white">Store Audit Requested!</h4>
                        <p class="text-sm text-slate-400 mt-2 max-w-sm">Our Lead conversion Engineers are tracking performance indicators for your URL. Expect an analysis summary shortly.</p>
                        <button onclick="document.getElementById('storeSuccess').classList.add('hidden')" class="mt-6 px-4 py-2 bg-slate-800 rounded-lg text-xs text-white">Submit Another</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CASE STUDIES SECTION -->
    <section id="case-studies" class="py-24 bg-slate-900/50 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Success Stories</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Engineering Enterprise Growth</h2>
                <p class="text-slate-400 mt-4">Take a look inside real project challenges we solved, including architectures and the final metrics achieved.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="caseStudiesGrid">
                <!-- Loaded cleanly from original definitions -->
            </div>
        </div>
    </section>

    <!-- CLIENT TESTIMONIALS -->
    <section class="py-24 bg-slate-950 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Client Feedback</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">What Global Merchants Say</h2>
                <p class="text-slate-400 mt-4">Verified client reviews regarding speed, support, and visual UI architecture.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8" id="testimonialsGrid">
                <!-- Populated dynamically -->
            </div>
        </div>
    </section>

    <!-- BLOG SECTION -->
    <section id="blog" class="py-24 bg-slate-900/50 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Industry Insights</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">E-commerce Blueprint & Strategies</h2>
                <p class="text-slate-400 mt-4">In-depth guides designed to increase retention, layout efficiency, and core performance.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6" id="blogGrid">
                <!-- Injected beautifully -->
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="py-24 bg-slate-950 border-t border-slate-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">FAQ</span>
                <h2 class="text-3xl font-extrabold text-white mt-2">Frequently Answered Queries</h2>
                <p class="text-slate-400 mt-3">Find fast answers concerning timelines, custom operations, platforms, and maintenance parameters.</p>
            </div>

            <div class="space-y-4" id="faqContainer">
                <!-- Accordion injected dynamically -->
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact" class="py-24 bg-gradient-to-t from-slate-950 to-slate-900 border-t border-slate-800 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Get in Touch</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Start Your Store Build</h2>
                        <p class="text-slate-400 mt-4">Ready to build or scale? Fill out our dynamic planner. On submission, we will instantly link your detailed brief to our technical director via WhatsApp.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500">
                                <i class="fa-regular fa-envelope text-lg"></i>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">EMAIL INQUIRIES</span>
                                <a href="mailto:<?= sanitize_output($settings['contact_email']) ?>" class="text-white hover:text-brand-500 font-medium transition-colors"><?= sanitize_output($settings['contact_email']) ?></a>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">WHATSAPP AGENT</span>
                                <a href="https://wa.me/<?= sanitize_output($settings['whatsapp_number']) ?>" target="_blank" class="text-white hover:text-brand-500 font-medium transition-colors">+<?= sanitize_output($settings['whatsapp_number']) ?></a>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500">
                                <i class="fa-brands fa-telegram text-lg"></i>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">TELEGRAM CHANNELS</span>
                                <a href="https://t.me/<?= sanitize_output($settings['telegram_username']) ?>" target="_blank" class="text-white hover:text-brand-500 font-medium transition-colors">@<?= sanitize_output($settings['telegram_username']) ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="glass p-8 rounded-2xl border border-slate-800/80">
                        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                            <i class="fa-regular fa-file-lines text-brand-500"></i> Project Inquiry Blueprint
                        </h3>

                        <form id="contactForm" class="space-y-4">
                            <input type="hidden" name="form_type" value="contact">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Full Name *</label>
                                    <input type="text" required name="full_name" placeholder="John Doe" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Email Address *</label>
                                    <input type="email" required name="email" placeholder="john@example.com" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">WhatsApp Number *</label>
                                    <input type="tel" required name="whatsapp_num" placeholder="+1 (555) 000-0000" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Business/Brand Name *</label>
                                    <input type="text" required name="brand_name" placeholder="Acme Corp" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Service Needed *</label>
                                    <select required name="service" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                        <option value="Shopify Store Development">Shopify Store Development</option>
                                        <option value="Shopify Redesign">Shopify Redesign</option>
                                        <option value="SEO Optimization">SEO Optimization</option>
                                        <option value="Marketing Setup">Marketing Setup</option>
                                        <option value="Speed Optimization">Speed Optimization</option>
                                        <option value="Bug Fixes">Bug Fixes</option>
                                        <option value="Website Design">Website Design</option>
                                        <option value="UI/UX Design">UI/UX Design</option>
                                        <option value="Other Service">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Budget Range *</label>
                                    <select required name="budget" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                        <option value="Under $1,000">Under $1,000</option>
                                        <option value="$1,000 - $3,000">$1,000 - $3,000</option>
                                        <option value="$3,000 - $10,000">$3,000 - $10,000</option>
                                        <option value="$10,000+">$10,000+</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Project Deadline *</label>
                                    <select required name="deadline" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                                        <option value="Urgent (1-2 Weeks)">Urgent (1-2 Weeks)</option>
                                        <option value="Standard (3-4 Weeks)">Standard (3-4 Weeks)</option>
                                        <option value="Flexible (1+ Month)">Flexible (1+ Month)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Project Description *</label>
                                <textarea required name="description" rows="3" placeholder="Describe what you want us to build or optimize..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Goals to Achieve *</label>
                                <input type="text" required name="goals" placeholder="e.g., Increase conversion rate by 3%, migrate smoothly from WooCommerce" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500">
                            </div>

                            <button type="submit" class="w-full py-4 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-lg tracking-wide transition-all duration-300 transform flex items-center justify-center space-x-2 shadow-lg shadow-brand-500/20">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                <span>Submit Brief & Start WhatsApp Chat</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 border-t border-slate-900 py-16 text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded bg-brand-500 flex items-center justify-center text-white">
                            <i class="fa-brands fa-shopify"></i>
                        </div>
                        <span class="font-bold text-white">OLUSEGUN GLOBAL</span>
                    </div>
                    <p class="text-xs text-slate-400">
                        Official Shopify Development Partners specializing in fast, structured, conversion-centric storefront engineering.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://wa.me/<?= sanitize_output($settings['whatsapp_number']) ?>" class="hover:text-white transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="https://t.me/<?= sanitize_output($settings['telegram_username']) ?>" class="hover:text-white transition-colors"><i class="fa-brands fa-telegram"></i></a>
                        <a href="mailto:<?= sanitize_output($settings['contact_email']) ?>" class="hover:text-white transition-colors"><i class="fa-regular fa-envelope"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4 text-xs tracking-wider uppercase">Quick Actions</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#home" class="hover:text-white transition-colors">Home Page</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About our Team</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Digital Services</a></li>
                        <li><a href="#shopify-expert" class="hover:text-white transition-colors">Shopify Expert Hub</a></li>
                        <li><a href="#audits" class="hover:text-white transition-colors">Free Site Audits</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4 text-xs tracking-wider uppercase">Core Solutions</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#services" class="hover:text-white transition-colors">Shopify Store Setup</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">E-commerce Redesign</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">SEO & Positioning</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Page Speed Architecture</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Strategic Marketing Setup</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4 text-xs tracking-wider uppercase">Legal Frameworks</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="javascript:void(0)" onclick="openLegalModal('privacy')" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="javascript:void(0)" onclick="openLegalModal('terms')" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                        <li><a href="javascript:void(0)" onclick="openLegalModal('refund')" class="hover:text-white transition-colors">Refund Policy</a></li>
                        <li><a href="javascript:void(0)" onclick="openLegalModal('cookie')" class="hover:text-white transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-900 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p><?= $settings['footer_copyright'] ?></p>
                <p>Designed for professional conversion performance.</p>
            </div>
        </div>
    </footer>

    <!-- DYNAMIC UNIFIED POPUP MODAL (Preserves all beautiful scripts & interactivity) -->
    <div id="unifiedModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-950/90 transition-opacity" aria-hidden="true" onclick="closeUnifiedModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <!-- Header -->
                <div class="bg-slate-950 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="modalIconBox" class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center text-brand-500"></div>
                        <div>
                            <h3 class="text-lg font-bold text-white leading-tight" id="modalTitle">Modal Title</h3>
                            <p class="text-xs text-slate-400" id="modalSubtitle">Category Subtitle</p>
                        </div>
                    </div>
                    <button onclick="closeUnifiedModal()" class="text-slate-400 hover:text-white text-xl">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-8 max-h-[60vh] overflow-y-auto text-slate-300 text-sm space-y-6" id="modalBody"></div>

                <!-- Footer -->
                <div class="bg-slate-950 px-6 py-4 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-xs text-slate-400">Have specific requirements? Let’s talk layout structure.</span>
                    <div class="flex space-x-3 w-full sm:w-auto">
                        <button onclick="closeUnifiedModal()" class="flex-1 sm:flex-none px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-semibold">Close</button>
                        <a id="modalCta" href="#contact" onclick="closeUnifiedModal()" class="flex-1 sm:flex-none px-5 py-2 bg-brand-500 hover:bg-brand-600 text-white rounded-lg text-xs font-semibold text-center">Discuss This Project</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DATA SCRIPT & FRONTEND BEHAVIORS -->
    <script>
        // Toggle mobile drawer
        const mobileMenu = document.getElementById('mobileMenu');
        function toggleMenu() {
            mobileMenu.classList.toggle('hidden');
        }
        document.getElementById('mobileMenuBtn').addEventListener('click', toggleMenu);

        // Header Background on Scroll
        window.addEventListener('scroll', () => {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('bg-slate-950/95', 'shadow-lg');
            } else {
                header.classList.remove('bg-slate-950/95', 'shadow-lg');
            }
        });

        // -------------------------------------------------------------
        // fallback arrays for dynamic sections (keeps page stable and beautiful)
        // -------------------------------------------------------------
        const STATIC_SERVICES = [
            {
                id: "shopify-dev",
                title: "Shopify Store Development",
                icon: "fa-brands fa-shopify",
                desc: "Complete production-ready Shopify builds utilizing lightning-fast architectures tailored for global sales.",
                benefits: ["Fully customizable page-builder structures", "Native support for internationalization", "High-conversion checkout styling"],
                results: "Faster catalog discovery, streamlined path-to-purchase, and reliable server-side performance.",
                problemsSolved: "Clunky theme architectures, poorly mapped site flows, checkout blockages, and non-responsive product pages.",
                whyNeeded: "A slow, non-optimized e-commerce platform immediately drains acquisition spend."
            },
            {
                id: "shopify-redesign",
                title: "Shopify Store Redesign",
                icon: "fa-solid fa-wand-magic-sparkles",
                desc: "Modern visual overhauls to align your current store layout with established premium brand identities.",
                benefits: ["Upgraded customer visual pathways", "Optimized image rendering systems", "Modern interaction models"],
                results: "Reduced bounce rates, prolonged session times, and instantly improved brand positioning.",
                problemsSolved: "Outdated layouts, misaligned font hierarchies, visual clutter, and complex navigation structures.",
                whyNeeded: "Modern online shoppers associate design quality with operational safety."
            },
            {
                id: "shopify-seo",
                title: "Shopify SEO Optimization",
                icon: "fa-solid fa-chart-line",
                desc: "Complete organic visibility strategy targeting high-intent commercial keywords without wasting resources.",
                benefits: ["Strict structured data (JSON-LD)", "Optimized search engine tags", "Refined search crawl paths"],
                results: "Substantial growth in targeted organic positioning, lower reliance on paid social.",
                problemsSolved: "Invisible search listings, duplicate tag indexes, slow product rankings.",
                whyNeeded: "While ad costs continue to rise, organic search traffic remains highly lucrative."
            }
        ];

        const SHOPIFY_EXPERT_ITEMS = [
            {
                id: "sh-partner",
                title: "Shopify Partner Services",
                icon: "fa-solid fa-handshake-simple",
                desc: "Direct access to our verified merchant account ecosystem for seamless store building and expert management.",
                problemsSolved: "Complex sandbox setups, billing platform confusion, and developer transfer friction.",
                whyNeeded: "Partner accounts allow for secure, risk-free store construction and testing without immediate subscription fees."
            },
            {
                id: "sh-setup",
                title: "Expert Store Setup",
                icon: "fa-solid fa-store",
                desc: "Complete baseline configuration spanning taxes, international shipping, payments, and checkout settings.",
                problemsSolved: "Incorrect tax reporting, failed checkout currencies, and shipping rate errors.",
                whyNeeded: "A flawless baseline setup ensures a seamless and professional experience for your first customer."
            },
            {
                id: "sh-import",
                title: "Bulk Product Import",
                icon: "fa-solid fa-file-import",
                desc: "Highly organized product schema mapping with variant organization and clean taxonomy.",
                problemsSolved: "Mismatched variant attributes, broken image maps, and missing SEO metadata.",
                whyNeeded: "Clean catalog taxonomy prevents customer confusion and ensures reliable navigation and filtering."
            }
        ];

        const CASE_STUDIES = [
            {
                id: "cs-fashion",
                client: "Aura Premium Apparel",
                type: "Migration & Redesign",
                image: "https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=600&q=80",
                challenge: "Migrating 4,000 SKUs from WooCommerce with slow load times and zero conversion growth.",
                solution: "Designed a lightweight, highly responsive Shopify OS 2.0 theme built for custom content.",
                results: "Conversion rate increased from 1.2% to 3.4%, and page load time was cut by 64%.",
                prose: "Aura Premium Apparel faced structural issues due to a heavy WooCommerce configuration. By migrating their store to a modern, customized Shopify setup, we optimized their product discovery path, resulting in a much cleaner, faster shopping experience."
            },
            {
                id: "cs-electronics",
                client: "Nexis Smart Devices",
                type: "Speed & SEO Setup",
                image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80",
                challenge: "High ad spend with low conversion rates due to mobile performance issues.",
                solution: "Eliminated heavy redundant apps, compressed image sizes, and implemented lazy-loading scripts.",
                results: "Mobile speed score improved from 28 to 94, and ad-to-cart conversions increased by 44%.",
                prose: "Nexis Smart Devices was losing traffic due to slow mobile performance. We resolved this by optimizing their image delivery, styling critical CSS pathways, and streamlining third-party tracking scripts."
            }
        ];

        const TESTIMONIALS = [
            {
                name: "Sarah Jenkins",
                role: "Director, Aura Premium Apparel",
                stars: 5,
                type: "Migration & Redesign",
                feedback: "Working with Olusegun Global Solutions Ltd was a smooth process. They handled our complex migration with care, resulting in a cleaner store and a noticeable increase in our conversion rate."
            },
            {
                name: "Marcus Thorne",
                role: "Founder, Nexis Devices",
                stars: 5,
                type: "Speed Optimization",
                feedback: "Our mobile speed score went from 28 to 94. Our customer support inquiries regarding checkout delays have completely stopped, and our conversions have improved."
            }
        ];

        const STATIC_BLOGS = [
            {
                id: "b-why-shopify",
                title: "Why Shopify Is The Best E-commerce Platform",
                img: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=400&q=80",
                summary: "An analytical look at Shopify's secure infrastructure, checkout features, and global ecosystem.",
                content: "<p>When selecting an e-commerce platform, security, performance, and conversion design are key considerations. Shopify has established itself as an industry leader for brands of all sizes.</p>"
            },
            {
                id: "b-seo",
                title: "Shopify SEO Strategies For More Sales",
                img: "https://images.unsplash.com/photo-1571721795195-a2ca2d3370a9?auto=format&fit=crop&w=400&q=80",
                summary: "How to optimize your search presence, clean up duplicate pages, and improve organic performance.",
                content: "<p>Organic search is a highly valuable source of traffic. However, many merchants miss out on organic search opportunities because of simple setup issues.</p>"
            }
        ];

        const FAQS = [
            { q: "How long does Shopify development take?", a: "A customized Shopify store development project typically takes between 2 to 4 weeks." },
            { q: "Do you provide SEO services?", a: "Yes. Our SEO services cover semantic markup, structured product data (JSON-LD), canonical URL mapping, and page speed index cleaning." }
        ];

        // -------------------------------------------------------------
        // Rendering Loops with DB fallback integrations
        // -------------------------------------------------------------
        
        // Services
        const servicesGrid = document.getElementById('servicesGrid');
        const dbServicesRaw = <?= json_encode($db_services) ?>;
        const activeServices = dbServicesRaw.length > 0 ? dbServicesRaw : STATIC_SERVICES;

        activeServices.forEach(service => {
            const card = document.createElement('div');
            card.className = "glass p-6 rounded-2xl border border-slate-800 glow-hover flex flex-col justify-between transition-all duration-300";
            card.innerHTML = `
                <div>
                    <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500 mb-6">
                        <i class="${service.icon || 'fa-solid fa-cube'} text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">${service.title}</h3>
                    <p class="text-sm text-slate-400 mb-6 line-clamp-3">${service.description || service.desc}</p>
                </div>
                <div>
                    <div class="border-t border-slate-800 pt-4 mt-2">
                        <button onclick="openServiceModal('${service.id || service.service_key}')" class="text-xs font-semibold text-brand-500 hover:text-brand-600 flex items-center gap-1.5 transition-colors">
                            Read Detailed Blueprint <i class="fa-solid fa-arrow-right-long"></i>
                        </button>
                    </div>
                </div>
            `;
            servicesGrid.appendChild(card);
        });

        // Shopify Expert
        const shopifyExpertGrid = document.getElementById('shopifyExpertGrid');
        SHOPIFY_EXPERT_ITEMS.forEach(item => {
            const card = document.createElement('div');
            card.className = "bg-slate-900/60 border border-slate-800/80 p-6 rounded-2xl hover:border-emerald-500/30 transition-all duration-300 flex flex-col justify-between";
            card.innerHTML = `
                <div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-950 flex items-center justify-center text-brand-500 mb-5">
                        <i class="${item.icon}"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">${item.title}</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">${item.desc}</p>
                </div>
                <div class="border-t border-slate-800/80 pt-4 mt-2">
                    <button onclick="openShopifyExpertModal('${item.id}')" class="text-[11px] font-bold text-brand-500 hover:text-brand-600 uppercase tracking-wider flex items-center gap-1">
                        Read System Blueprint <i class="fa-solid fa-angle-right"></i>
                    </button>
                </div>
            `;
            shopifyExpertGrid.appendChild(card);
        });

        // Case Studies
        const caseStudiesGrid = document.getElementById('caseStudiesGrid');
        CASE_STUDIES.forEach(cs => {
            const card = document.createElement('div');
            card.className = "glass rounded-2xl border border-slate-800 overflow-hidden hover:border-brand-500/20 transition-all duration-300 flex flex-col justify-between";
            card.innerHTML = `
                <div>
                    <div class="h-48 relative overflow-hidden">
                        <img src="${cs.image}" alt="" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 bg-slate-950/80 text-brand-500 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border border-brand-500/20">${cs.type}</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <h4 class="text-xl font-bold text-white">${cs.client}</h4>
                        <div class="space-y-2 text-xs">
                            <p class="text-slate-400"><strong class="text-slate-300">Challenge:</strong> ${cs.challenge}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 pt-0">
                    <div class="bg-slate-950/80 p-3 rounded-lg border border-slate-800 text-center mb-4">
                        <span class="text-xs text-brand-500 font-bold block">${cs.results}</span>
                    </div>
                    <button onclick="openCaseStudyModal('${cs.id}')" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-semibold transition-colors">
                        Read Full Case Study
                    </button>
                </div>
            `;
            caseStudiesGrid.appendChild(card);
        });

        // Testimonials
        const testimonialsGrid = document.getElementById('testimonialsGrid');
        const dbTestimonialsRaw = <?= json_encode($db_testimonials) ?>;
        const activeTestimonials = dbTestimonialsRaw.length > 0 ? dbTestimonialsRaw : TESTIMONIALS;

        activeTestimonials.forEach(t => {
            const card = document.createElement('div');
            card.className = "glass p-6 rounded-2xl border border-slate-800 flex flex-col justify-between";
            card.innerHTML = `
                <div>
                    <div class="flex items-center space-x-1 mb-4 text-amber-500 text-xs">
                        ${Array(t.star_rating || t.stars || 5).fill('<i class="fa-solid fa-star"></i>').join('')}
                    </div>
                    <p class="text-sm text-slate-300 italic leading-relaxed mb-6">"${t.feedback}"</p>
                </div>
                <div class="border-t border-slate-800 pt-4 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-bold text-white">${t.client_name || t.name}</span>
                        <span class="text-[11px] text-slate-400 block">${t.client_role || t.role}</span>
                    </div>
                    <span class="text-[10px] bg-brand-500/10 text-brand-500 border border-brand-500/20 rounded px-2 py-0.5 uppercase font-bold">${t.project_type || t.type}</span>
                </div>
            `;
            testimonialsGrid.appendChild(card);
        });

        // Blogs
        const blogGrid = document.getElementById('blogGrid');
        const dbBlogsRaw = <?= json_encode($db_blogs) ?>;
        const activeBlogs = dbBlogsRaw.length > 0 ? dbBlogsRaw : STATIC_BLOGS;

        activeBlogs.forEach(b => {
            const card = document.createElement('div');
            card.className = "glass rounded-2xl border border-slate-800/80 overflow-hidden flex flex-col justify-between hover:border-brand-500/20 transition-all duration-300";
            card.innerHTML = `
                <div>
                    <img src="${b.featured_image || b.img}" alt="" class="w-full h-40 object-cover">
                    <div class="p-5">
                        <h4 class="text-base font-bold text-white mb-2 line-clamp-2">${b.title}</h4>
                        <p class="text-xs text-slate-400 line-clamp-3">${b.summary}</p>
                    </div>
                </div>
                <div class="p-5 pt-0">
                    <button onclick="openBlogModal('${b.id || b.slug}')" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-brand-500 hover:text-brand-600 rounded-lg text-xs font-semibold transition-colors border border-slate-800">
                        Read Full Insight
                    </button>
                </div>
            `;
            blogGrid.appendChild(card);
        });

        // FAQ
        const faqContainer = document.getElementById('faqContainer');
        FAQS.forEach((faq, index) => {
            const div = document.createElement('div');
            div.className = "glass rounded-xl border border-slate-800/80 overflow-hidden";
            div.innerHTML = `
                <button onclick="toggleFaq(${index})" class="w-full px-6 py-4 text-left flex items-center justify-between text-white font-medium hover:bg-slate-900/40 transition-colors">
                    <span class="text-sm pr-4">${faq.q}</span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-300" id="faqIcon-${index}"></i>
                </button>
                <div class="hidden px-6 pb-5 text-xs text-slate-400 leading-relaxed border-t border-slate-900 pt-3" id="faqAnswer-${index}">
                    ${faq.a}
                </div>
            `;
            faqContainer.appendChild(div);
        });

        function toggleFaq(index) {
            const ans = document.getElementById(`faqAnswer-${index}`);
            const icon = document.getElementById(`faqIcon-${index}`);
            ans.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // -------------------------------------------------------------
        // Interactive Modals Configuration
        // -------------------------------------------------------------
        const modal = document.getElementById('unifiedModal');
        const modalIconBox = document.getElementById('modalIconBox');
        const modalTitle = document.getElementById('modalTitle');
        const modalSubtitle = document.getElementById('modalSubtitle');
        const modalBody = document.getElementById('modalBody');

        function openUnifiedModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeUnifiedModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        window.openServiceModal = function(id) {
            const service = activeServices.find(s => s.id === id || s.service_key === id);
            if (!service) return;

            modalIconBox.innerHTML = `<i class="${service.icon || 'fa-solid fa-cube'} text-xl"></i>`;
            modalTitle.textContent = service.title;
            modalSubtitle.textContent = "Service Execution Blueprint";

            modalBody.innerHTML = `
                <div class="space-y-4">
                    <p class="text-sm leading-relaxed">${service.description || service.desc}</p>
                    <h5 class="font-bold text-white text-xs uppercase tracking-wider">Key Benefits</h5>
                    <ul class="list-disc pl-5 space-y-1 text-slate-400">
                        ${(service.benefits || []).map(b => `<li>${b}</li>`).join('')}
                    </ul>
                </div>
            `;
            openUnifiedModal();
        }

        window.openShopifyExpertModal = function(id) {
            const item = SHOPIFY_EXPERT_ITEMS.find(s => s.id === id);
            if (!item) return;

            modalIconBox.innerHTML = `<i class="${item.icon} text-xl"></i>`;
            modalTitle.textContent = item.title;
            modalSubtitle.textContent = "Verified Partner Solutions";
            modalBody.innerHTML = `<p class="leading-relaxed">${item.desc}</p>`;
            openUnifiedModal();
        }

        window.openCaseStudyModal = function(id) {
            const item = CASE_STUDIES.find(c => c.id === id);
            if (!item) return;

            modalIconBox.innerHTML = `<i class="fa-solid fa-square-poll-horizontal text-xl"></i>`;
            modalTitle.textContent = item.client;
            modalSubtitle.textContent = item.type;
            modalBody.innerHTML = `<p class="leading-relaxed">${item.prose}</p>`;
            openUnifiedModal();
        }

        window.openBlogModal = function(id) {
            const item = activeBlogs.find(b => b.id === id || b.slug === id);
            if (!item) return;

            modalIconBox.innerHTML = `<i class="fa-regular fa-newspaper text-xl"></i>`;
            modalTitle.textContent = item.title;
            modalSubtitle.textContent = "Insights & Strategies";
            modalBody.innerHTML = `
                <div class="space-y-4">
                    <img src="${item.featured_image || item.img}" class="w-full h-48 object-cover rounded-xl mb-4">
                    <div class="prose prose-invert text-sm leading-relaxed">${item.content}</div>
                </div>
            `;
            openUnifiedModal();
        }

        // Legal Modals Lookup
        const LEGAL_DOCS = {
            privacy: { title: "Privacy Policy", body: "<p>We prioritize your security. All parsed diagnostic customer insights and database entries are held in strict privacy and isolation parameters.</p>" },
            terms: { title: "Terms & Conditions", body: "<p>Services are governed under professional performance agreements customized on a per-merchant basis.</p>" },
            refund: { title: "Refund Policy", body: "<p>Milestone progress billing runs align strictly with our developer resource allocation timelines.</p>" },
            cookie: { title: "Cookie Policy", body: "<p>We utilize standard functional caching and statistics tracking parameters to keep page experiences fast.</p>" }
        };

        window.openLegalModal = function(docKey) {
            const doc = LEGAL_DOCS[docKey];
            if (!doc) return;

            modalIconBox.innerHTML = `<i class="fa-solid fa-gavel text-xl"></i>`;
            modalTitle.textContent = doc.title;
            modalSubtitle.textContent = "Compliance Framework";
            modalBody.innerHTML = doc.body;
            openUnifiedModal();
        }

        // -------------------------------------------------------------
        // Form Processing via AJAX Endpoints
        // -------------------------------------------------------------
        document.getElementById('seoAuditForm').addEventListener('submit', function(e) {
            e.preventDefault();
            sendFormData(new FormData(this), 'seoSuccess');
        });

        document.getElementById('storeAuditForm').addEventListener('submit', function(e) {
            e.preventDefault();
            sendFormData(new FormData(this), 'storeSuccess');
        });

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('submit-handler.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(data => {
                if (data.redirect_payload) {
                    const p = data.redirect_payload;
                    const text = `*OLUSEGUN GLOBAL SOLUTIONS LTD*\n\n• *Name:* ${p.full_name}\n• *Email:* ${p.email}\n• *WhatsApp:* ${p.whatsapp}\n• *Brand:* ${p.brand}\n• *Service:* ${p.service}\n• *Budget:* ${p.budget}\n• *Timeline:* ${p.deadline}\n\n*Description:* ${p.desc}\n\n*Goals:* ${p.goals}`;
                    
                    const waNum = "<?= sanitize_output($settings['whatsapp_number']) ?>";
                    window.open(`https://wa.me/${waNum}?text=${encodeURIComponent(text)}`, '_blank');
                    document.getElementById('contactForm').reset();
                } else {
                    alert(data.message || 'An error occurred during submission.');
                }
            });
        });

        function sendFormData(formData, successId) {
            fetch('submit-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById(successId).classList.remove('hidden');
                } else {
                    alert(data.message || 'An error occurred during submission.');
                }
            });
        }
    </script>
</body>
</html>
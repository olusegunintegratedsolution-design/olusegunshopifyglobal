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
        'contact_email' => 'olusegunintegratedsolution@gmail.com',
        'whatsapp_number' => '12052371919',
        'telegram_username' => 'Fasinufelix',
        'footer_copyright' => '&copy; 2025 OLUSEGUN GLOBAL SOLUTIONS LTD. All Rights Reserved.'
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
    <title><?= sanitize_output($settings['meta_title']) ?></title>
    <meta name="description" content="<?= sanitize_output($settings['meta_description']) ?>">
    <meta name="keywords" content="<?= sanitize_output($settings['meta_keywords']) ?>">

    <!-- STABLE PRE-COMPILED TAILWIND CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Premium Custom Styles & Transitions -->
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
        .glow-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glow-hover:hover {
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.25);
            border-color: rgba(16, 185, 129, 0.4);
            transform: translateY(-4px);
        }
        .text-brand-500 { color: #10b981; }
        .bg-brand-500 { background-color: #10b981; }
        .hover\:bg-brand-600:hover { background-color: #059669; }
        .border-brand-500\/20 { border-color: rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 w-full z-50 glass border-b border-gray-800 transition-all duration-300" id="mainHeader">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#home" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-lg bg-brand-500 flex items-center justify-center glow-green transition-transform duration-300 group-hover:scale-105">
                    <i class="fa-brands fa-shopify text-white text-xl"></i>
                </div>
                <div>
                    <span class="font-extrabold text-lg tracking-tight block text-white group-hover:text-brand-500 transition-colors">OLUSEGUN</span>
                    <span class="text-xs text-gray-400 block -mt-1 tracking-widest font-semibold uppercase">GLOBAL SOLUTIONS</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="#home" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">Home</a>
                <a href="#about" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">About</a>
                <a href="#services" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">Services</a>
                <a href="#shopify-expert" class="text-sm font-medium text-brand-500 hover:text-brand-600 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Shopify Partner
                </a>
                <a href="#audits" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">Audits</a>
                <a href="#case-studies" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">Case Studies</a>
                <a href="#blog" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">Insights</a>
                <a href="#faq" class="text-sm font-medium text-gray-300 hover:text-brand-500 transition-colors">FAQ</a>
            </nav>

            <div class="hidden lg:flex items-center space-x-4">
                <a href="#contact" class="px-5 py-2.5 rounded-lg bg-brand-500 hover:bg-brand-600 text-white font-medium text-sm transition-all duration-300 hover:-translate-y-0.5 shadow-lg shadow-brand-500/10">
                    Get Free Consult
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button class="lg:hidden text-gray-300 hover:text-white focus:outline-none" id="mobileMenuBtn" aria-label="Toggle Navigation Menu">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Drawer Menu -->
        <div class="hidden lg:hidden glass border-t border-gray-800 absolute top-20 left-0 w-full p-6 space-y-4" id="mobileMenu">
            <a href="#home" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Home</a>
            <a href="#about" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">About</a>
            <a href="#services" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Services</a>
            <a href="#shopify-expert" class="block text-brand-500 hover:text-brand-600 font-medium py-2" onclick="toggleMenu()">Shopify Partner Services</a>
            <a href="#audits" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Audits</a>
            <a href="#case-studies" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Case Studies</a>
            <a href="#blog" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">Insights/Blog</a>
            <a href="#faq" class="block text-gray-300 hover:text-brand-500 font-medium py-2" onclick="toggleMenu()">FAQ</a>
            <a href="#contact" class="block text-center bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-lg" onclick="toggleMenu()">Get Free Consult</a>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="home" class="relative pt-32 pb-24 md:pt-44 md:pb-36 bg-gradient-to-b from-gray-900 to-gray-950 overflow-hidden">
        <div class="absolute top-1/4 left-1/10 w-96 h-96 bg-brand-500/10 rounded-full filter blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center space-x-2 bg-gray-900 border border-gray-800 px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wide text-brand-500">
                        <i class="fa-brands fa-shopify"></i>
                        <span>VERIFIED SHOPIFY PARTNER</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-none">
                        Scale Your Store Globally with <span class="text-brand-500">Shopify Experts</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-gray-300 max-w-xl">
                        Olusegun Global Solutions Ltd designs, builds, and optimizes ultra-premium, high-converting digital storefronts. Let’s turn your traffic into enterprise-level sales.
                    </p>

                    <div class="pt-2 grid grid-cols-3 gap-4 border-t border-gray-800 max-w-lg">
                        <div>
                            <span class="block text-2xl font-bold text-white">150+</span>
                            <span class="text-xs text-gray-400">Stores Built</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-white">99%</span>
                            <span class="text-xs text-gray-400">Success Rate</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-white">$10M+</span>
                            <span class="text-xs text-gray-400">Client Revenue</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#contact" class="px-8 py-4 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg text-center transition-all duration-300 hover:shadow-lg hover:shadow-brand-500/25">
                            Launch Your Project
                        </a>
                        <a href="#services" class="px-8 py-4 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-lg text-center border border-gray-700 transition-all duration-300">
                            Explore Services
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 relative mt-8 lg:mt-0">
                    <div class="glass rounded-2xl p-6 glow-green relative overflow-hidden border border-gray-700/50">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <span class="text-xs font-semibold text-brand-500 tracking-wider">OLUSEGUN DASHBOARD</span>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-950 rounded-xl p-4 border border-gray-800">
                                <span class="text-xs text-gray-400 block">Total Revenue Managed</span>
                                <div class="flex items-baseline space-x-2 mt-1">
                                    <span class="text-3xl font-extrabold text-white">$4,821,304</span>
                                    <span class="text-xs text-emerald-400 font-semibold">+42.1%</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-950 rounded-xl p-3 border border-gray-800">
                                    <span class="text-xs text-gray-400 block">Conversion Rate</span>
                                    <span class="text-lg font-bold text-white mt-1 block">4.82%</span>
                                </div>
                                <div class="bg-gray-950 rounded-xl p-3 border border-gray-800">
                                    <span class="text-xs text-gray-400 block">Speed Score</span>
                                    <span class="text-lg font-bold text-brand-500 mt-1 block">98/100</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ABOUT US SECTION -->
    <section id="about" class="py-24 bg-gray-950 border-t border-gray-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Our Identity</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Who is Olusegun Global Solutions?</h2>
                <p class="text-gray-400 mt-4">We are an elite team of e-commerce specialists, engineers, and digital marketers focused on building flawless digital platforms for growing brands.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" alt="Olusegun Agency Team" class="w-full h-[400px] object-cover rounded-2xl brightness-90">
                </div>

                <div class="space-y-6">
                    <h3 class="text-2xl font-bold text-white">Empowering International E-commerce Scaling</h3>
                    <p class="text-gray-300">
                        At Olusegun Global Solutions Ltd, we combine highly specialized technical skillsets with cutting-edge design methodologies. As official Shopify Partners, we eliminate the complex technical blockages that prevent business growth.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500 mt-1">
                                <i class="fa-solid fa-bullseye text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Our Mission</h4>
                                <p class="text-sm text-gray-400">To build highly optimized, secure, and beautiful digital systems that unlock repeatable global revenue for our clients.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500 mt-1">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Our Vision</h4>
                                <p class="text-sm text-gray-400">To remain a top-tier global authority in Shopify Ecosystem Optimization.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="services" class="py-24 bg-gray-900 border-t border-gray-805">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Core Services</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Specialized Digital Capabilities</h2>
                <p class="text-gray-400 mt-4">We cover the entire lifecycle of your digital assets. Browse our professional service offerings below.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="servicesGrid"></div>
        </div>
    </section>

    <!-- SHOPIFY EXPERT SECTION -->
    <section id="shopify-expert" class="py-24 bg-gray-950 border-t border-gray-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 mb-16 border-b border-gray-800 pb-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center space-x-2 bg-gray-900 border border-gray-800 px-3 py-1 rounded-full text-xs font-semibold text-brand-500 mb-4">
                        <i class="fa-brands fa-shopify"></i>
                        <span>EXCLUSIVE SHOPIFY PARTNER ECOSYSTEM</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-extrabold text-white">Premium Shopify Partner Services</h2>
                    <p class="text-gray-300 mt-4 text-base sm:text-lg">
                        We don't just build sites; we craft complex e-commerce engines designed for conversions, customer retention, and massive structural scalability.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="shopifyExpertGrid"></div>
        </div>
    </section>

    <!-- STRATEGIC AUDITS (SEO & STORE ANALYSIS) -->
    <section id="audits" class="py-24 bg-gray-900 border-t border-gray-805 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Free Assessment</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Unlock Store Analysis Guides</h2>
                <p class="text-gray-400 mt-4">Submit your assets for a technical evaluation by our consulting team. No credit card required.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- SEO Audit Form -->
                <div class="glass p-8 rounded-2xl border border-gray-800 shadow-xl relative overflow-hidden">
                    <h3 class="text-xl font-bold text-white mb-6">SEO Analysis Request</h3>
                    <form id="seoAuditForm" class="space-y-4">
                        <input type="hidden" name="form_type" value="seo_audit">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Website URL *</label>
                            <input type="url" required name="url" placeholder="https://yourstore.com" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Business Name *</label>
                            <input type="text" required name="business_name" placeholder="E-commerce Store Name" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Industry *</label>
                                <input type="text" required name="industry" placeholder="Fashion, Tech, etc." class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Email Address *</label>
                                <input type="email" required name="email" placeholder="owner@store.com" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg text-sm transition-all duration-300">
                            Submit Free SEO Audit Request
                        </button>
                    </form>
                    <div id="seoSuccess" class="hidden absolute inset-0 bg-gray-950/95 flex flex-col items-center justify-center p-6 text-center">
                        <i class="fa-regular fa-circle-check text-5xl text-brand-500 mb-4"></i>
                        <h4 class="text-xl font-bold text-white">SEO Request Submitted!</h4>
                        <p class="text-sm text-gray-400 mt-2">We are analyzing your search metadata and will email you the full PDF report shortly.</p>
                        <button onclick="document.getElementById('seoSuccess').classList.add('hidden')" class="mt-6 px-4 py-2 bg-gray-800 rounded-lg text-xs text-white">Submit Another</button>
                    </div>
                </div>

                <!-- Store Analysis Form -->
                <div class="glass p-8 rounded-2xl border border-gray-800 shadow-xl relative overflow-hidden">
                    <h3 class="text-xl font-bold text-white mb-6">Store Conversion Audit</h3>
                    <form id="storeAuditForm" class="space-y-4">
                        <input type="hidden" name="form_type" value="store_audit">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Store URL *</label>
                            <input type="url" required name="store_url" placeholder="https://yourstore.myshopify.com" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Platform Used *</label>
                                <select required name="platform" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                                    <option value="Shopify">Shopify</option>
                                    <option value="WooCommerce">WooCommerce</option>
                                    <option value="Magento">Magento</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Monthly Revenue *</label>
                                <select required name="revenue" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500">
                                    <option value="0-5k">$0 - $5k</option>
                                    <option value="5k-20k">$5k - $20k</option>
                                    <option value="20k-100k">$20k - $100k</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Main Challenge *</label>
                            <textarea required name="challenge" rows="2" placeholder="Low conversion, poor loading speed..." class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-brand-500"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg text-sm transition-all duration-300">
                            Submit Store conversion Audit
                        </button>
                    </form>
                    <div id="storeSuccess" class="hidden absolute inset-0 bg-gray-950/95 flex flex-col items-center justify-center p-6 text-center">
                        <i class="fa-regular fa-circle-check text-5xl text-brand-500 mb-4"></i>
                        <h4 class="text-xl font-bold text-white">Store Audit Requested!</h4>
                        <p class="text-sm text-gray-400 mt-2">Expect your conversion overview report shortly.</p>
                        <button onclick="document.getElementById('storeSuccess').classList.add('hidden')" class="mt-6 px-4 py-2 bg-gray-800 rounded-lg text-xs text-white">Submit Another</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CASE STUDIES SECTION -->
    <section id="case-studies" class="py-24 bg-gray-950 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Success Stories</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Engineering Enterprise Growth</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="caseStudiesGrid"></div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="py-24 bg-gray-900 border-t border-gray-805">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Reviews</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">What Global Merchants Say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="testimonialsGrid"></div>
        </div>
    </section>

    <!-- BLOG SECTION -->
    <section id="blog" class="py-24 bg-gray-950 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Industry Insights</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">E-commerce Blueprint & Strategies</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="blogGrid"></div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="py-24 bg-gray-900 border-t border-gray-805">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white text-center mb-12">Frequently Answered Queries</h2>
            <div class="space-y-4" id="faqContainer"></div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact" class="py-24 bg-gradient-to-t from-gray-950 to-gray-900 border-t border-gray-805">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <span class="text-sm font-bold uppercase tracking-widest text-brand-500">Get in Touch</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Start Your Store Build</h2>
                        <p class="text-gray-400 mt-4">Fill out our dynamic planner. On submission, we will instantly link your detailed brief to our technical director via WhatsApp.</p>
                    </div>

                    <div class="space-y-6 text-sm">
                        <p><i class="fa-regular fa-envelope text-brand-500 mr-2"></i> <?= sanitize_output($settings['contact_email']) ?></p>
                        <p><i class="fa-brands fa-whatsapp text-brand-500 mr-2"></i> +<?= sanitize_output($settings['whatsapp_number']) ?></p>
                        <p><i class="fa-brands fa-telegram text-brand-500 mr-2"></i> @<?= sanitize_output($settings['telegram_username']) ?></p>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="glass p-8 rounded-2xl border border-gray-800">
                        <form id="contactForm" class="space-y-4">
                            <input type="hidden" name="form_type" value="contact">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" required name="full_name" placeholder="Full Name" class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-sm text-white">
                                <input type="email" required name="email" placeholder="Email Address" class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-sm text-white">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="tel" required name="whatsapp_num" placeholder="WhatsApp Number" class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-sm text-white">
                                <input type="text" required name="brand_name" placeholder="Business Name" class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-sm text-white">
                            </div>
                            <textarea required name="description" rows="3" placeholder="Describe your requirements..." class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-sm text-white"></textarea>
                            <input type="text" required name="goals" placeholder="Goals to Achieve" class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-sm text-white">
                            <button type="submit" class="w-full py-4 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-lg transition-all">Submit & Start WhatsApp Chat</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-950 border-t border-gray-900 py-16 text-gray-500 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="space-y-4">
                    <span class="font-bold text-white block">OLUSEGUN GLOBAL</span>
                    <p class="text-xs text-gray-400">Official Shopify Development Partners.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-xs tracking-wider uppercase">Actions</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Services</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-xs tracking-wider uppercase">Solutions</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#shopify-expert" class="hover:text-white transition-colors">Shopify Expert</a></li>
                        <li><a href="#audits" class="hover:text-white transition-colors">Free Audits</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-xs tracking-wider uppercase">Compliance</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="javascript:void(0)" onclick="openLegalModal('privacy')" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="javascript:void(0)" onclick="openLegalModal('terms')" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-900 pt-8 text-center text-gray-600">
                <p><?= $settings['footer_copyright'] ?></p>
            </div>
        </div>
    </footer>

    <!-- POPUP MODAL SYSTEM -->
    <div id="unifiedModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-950/90 transition-opacity" onclick="closeUnifiedModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-gray-900 border border-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-gray-950 px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="modalIconBox" class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center text-brand-500"></div>
                        <h3 class="text-lg font-bold text-white" id="modalTitle">Modal Title</h3>
                    </div>
                    <button onclick="closeUnifiedModal()" class="text-gray-400 hover:text-white text-xl">&times;</button>
                </div>
                <div class="px-6 py-8 max-h-[60vh] overflow-y-auto text-gray-300 text-sm space-y-4" id="modalBody"></div>
                <div class="bg-gray-950 px-6 py-4 border-t border-gray-800 text-right">
                    <button onclick="closeUnifiedModal()" class="px-5 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-xs font-semibold">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CODE INTERACTIONS IMPLEMENTATION -->
    <script>
        const mobileMenu = document.getElementById('mobileMenu');
        function toggleMenu() { mobileMenu.classList.toggle('hidden'); }
        document.getElementById('mobileMenuBtn').addEventListener('click', toggleMenu);

        // Header Background Transition
        window.addEventListener('scroll', () => {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('bg-gray-950', 'shadow-lg');
            } else {
                header.classList.remove('bg-gray-950', 'shadow-lg');
            }
        });

        // -------------------------------------------------------------
        // STATIC CONTENT BLUEPRINTS (Fallback Arrays)
        // -------------------------------------------------------------
        const STATIC_SERVICES = [
            { id: "s-dev", title: "Shopify Store Development", icon: "fa-brands fa-shopify", desc: "Complete production-ready Shopify builds utilizing fast OS 2.0 architectures.", benefits: ["Fully customizable theme profiles", "Dynamic page builders included", "Optimized shopping paths"], whyNeeded: "Standard, unoptimized themes lead to high cart drop-off rates.", problems: "Slow page load times, broken layouts, and clunky navigation pathways.", results: "Higher product discovery rates, polished UX design, and consistent sales performance." },
            { id: "s-redesign", title: "Shopify Store Redesign", icon: "fa-solid fa-wand-magic-sparkles", desc: "Modern visual overhauls to align your current store layout with premium brand identities.", benefits: ["Upgraded visual layouts", "Optimized navigation paths", "Clean font & color guidelines"], whyNeeded: "An outdated layout breaks immediate user trust.", problems: "High bounce rates, poor layout hierarchy, and non-responsive components.", results: "Increased user engagement, polished visual aesthetics, and lower bounce rates." },
            { id: "s-seo", title: "Shopify SEO Optimization", icon: "fa-solid fa-chart-line", desc: "Complete organic visibility strategy targeting high-intent commercial keywords.", benefits: ["Strict schema structure integration", "Sitemap path indexes checked", "Image alt tags optimized"], whyNeeded: "Without search visibility, you are leaving high-intent traffic behind.", problems: "Zero search listings, missing metadata, and bad indexing profiles.", results: "Sustainable organic search visibility and decreased reliance on paid social ads." },
            { id: "s-speed", title: "Shopify Speed Optimization", icon: "fa-solid fa-gauge-high", desc: "Clean technical code operations that minimize overall site assets and establish great platform loading metrics.", benefits: ["Heavy JS scripts postponed", "Lightweight image rendering configurations", "Unused theme app code cleaned"], whyNeeded: "Every second of delay costs conversion percentage points.", problems: "High loading lag, slow page-to-page navigation, and lost checkouts.", results: "Near-instant mobile load times, passing core web vitals performance scores." },
            { id: "s-bugs", title: "Shopify Bug Fixes", icon: "fa-solid fa-bug-slash", desc: "Rapid identification and correction of Javascript runtime errors and rendering issues across any theme setup.", benefits: ["Isolate code errors quickly", "Cross-browser mobile compatibility checks", "Secure script repairs"], whyNeeded: "Rendering faults look unprofessional and lead to lost cart sales.", problems: "Broken checkout elements, non-clickable buttons, and layout shifts.", results: "Clean script execution and consistent, uninterrupted browsing workflows." },
            { id: "s-marketing", title: "Shopify Marketing Setup", icon: "fa-solid fa-bullhorn", desc: "Integrated customer data tracking configurations linking custom pixels to leading social advertising platforms.", benefits: ["Exact pixel conversions tracked", "Server-to-server connection events", "Accurate client event matching"], whyNeeded: "Accurate metrics are required to optimize and scale ad campaigns.", problems: "Broken retargeting pixels and inaccurate data reporting indicators.", results: "Transparent metrics tracking and optimized return-on-ad-spend (ROAS)." }
        ];

        const SHOPIFY_EXPERT_ITEMS = [
            { id: "e-setup", title: "Store Setup", icon: "fa-solid fa-store", desc: "Complete baseline configuration spanning shipping, checkout settings, and international taxes." },
            { id: "e-import", title: "Product Import", icon: "fa-solid fa-file-import", desc: "Highly organized product schema mapping with variant organization and clean taxonomy." },
            { id: "e-migration", title: "Store Migration", icon: "fa-solid fa-shuffle", desc: "Zero-downtime database transfers from WooCommerce, Magento, or BigCommerce." }
        ];

        const CASE_STUDIES = [
            { id: "c-aura", title: "Aura Premium Apparel", type: "Migration & Redesign", challenge: "Migrating 4,000 SKUs from WooCommerce with slow load times.", results: "Conversion rate increased from 1.2% to 3.4%", prose: "Aura Premium Apparel suffered from complex layout loading lag. By migrating them to an optimized Shopify configuration, we streamlined catalog search paths, resulting in instant loading times and an immediate, measurable increase in conversion rates." },
            { id: "c-nexis", title: "Nexis Smart Devices", type: "Speed Optimization", challenge: "High mobile ad spend with low conversion rates.", results: "Mobile speed score improved from 28 to 94", prose: "Nexis Smart Devices experienced a high bounce rate on mobile devices. We isolated and postponed uninstalled script libraries, compressed product images, and set up lazy-loading parameters, improving checkout speeds." }
        ];

        const TESTIMONIALS = [
            { name: "Sarah Jenkins", role: "Director, Aura", stars: 5, feedback: "Professional execution. They cut our checkout bottlenecks and conversion rates grew instantly." },
            { name: "Marcus Thorne", role: "Founder, Nexis", stars: 5, feedback: "Our mobile loading times dropped to under 1.5 seconds. Relentless execution speed." }
        ];

        const BLOG_ARTICLES = [
            { id: "b-why", title: "Why Shopify Is The Best E-commerce Platform", desc: "An analytical study of checkout infrastructure.", img: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=400&q=80", content: "<p>Shopify provides a reliable, secure hosting infrastructure out of the box, handling PCI compliance and payment gateways natively. This security, combined with conversion tools like Shop Pay, makes it a leading choice for scaling brands.</p>" },
            { id: "b-seo", title: "Shopify SEO Strategies For More Sales", desc: "How to rank your collections on Search.", img: "https://images.unsplash.com/photo-1571721795195-a2ca2d3370a9?auto=format&fit=crop&w=400&q=80", content: "<p>Organic search is a highly valuable source of traffic. By optimizing metadata, using structured schema integration, and implementing clean canonical tags, you can capture high-intent buyers looking for your products.</p>" }
        ];

        const FAQS = [
            { q: "How long does Shopify development take?", a: "A customized Shopify store development project typically takes between 2 to 4 weeks." },
            { q: "Do you provide SEO services?", a: "Yes. Our SEO services cover semantic markup, structured product data (JSON-LD), canonical URL mapping, and page speed index cleaning." }
        ];

        // -------------------------------------------------------------
        // RENDER LOOPS
        // -------------------------------------------------------------
        
        // Services
        const servicesGrid = document.getElementById('servicesGrid');
        const dbServices = <?= json_encode($db_services) ?>;
        const activeServices = dbServices.length > 0 ? dbServices : STATIC_SERVICES;
        activeServices.forEach(s => {
            const card = document.createElement('div');
            card.className = "glass p-6 rounded-2xl border border-gray-800 glow-hover flex flex-col justify-between";
            card.innerHTML = `
                <div>
                    <div class="w-12 h-12 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500 mb-6">
                        <i class="${s.icon || 'fa-solid fa-cube'} text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">${s.title}</h3>
                    <p class="text-sm text-gray-400 mb-6 line-clamp-3">${s.description || s.desc}</p>
                </div>
                <div>
                    <button onclick="openServiceModal('${s.id || s.service_key}')" class="text-xs font-semibold text-brand-500 hover:text-brand-600 flex items-center gap-1.5">
                        Read Detailed Blueprint <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
            `;
            servicesGrid.appendChild(card);
        });

        // Shopify Expert
        const shopifyGrid = document.getElementById('shopifyExpertGrid');
        SHOPIFY_EXPERT_ITEMS.forEach(s => {
            const card = document.createElement('div');
            card.className = "bg-gray-900 border border-gray-800 p-6 rounded-2xl glow-hover flex flex-col justify-between";
            card.innerHTML = `
                <div>
                    <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center text-brand-500 mb-4">
                        <i class="${s.icon}"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">${s.title}</h4>
                    <p class="text-xs text-gray-400 mb-4">${s.desc}</p>
                </div>
                <div>
                    <button onclick="openShopifyModal('${s.id}')" class="text-xs font-semibold text-brand-500">Read Blueprint &rarr;</button>
                </div>
            `;
            shopifyGrid.appendChild(card);
        });

        // Case Studies
        const csGrid = document.getElementById('caseStudiesGrid');
        CASE_STUDIES.forEach(c => {
            const card = document.createElement('div');
            card.className = "glass rounded-2xl border border-gray-800 p-6 flex flex-col justify-between glow-hover";
            card.innerHTML = `
                <div>
                    <span class="text-brand-500 text-[10px] font-bold uppercase tracking-wider">${c.type}</span>
                    <h4 class="text-xl font-bold text-white mt-2">${c.title}</h4>
                    <p class="text-xs text-gray-400 mt-2 line-clamp-2">${c.challenge}</p>
                </div>
                <div class="mt-6">
                    <div class="bg-gray-950 p-3 rounded-lg border border-gray-800 text-center mb-4">
                        <span class="text-xs text-brand-500 font-bold block">${c.results}</span>
                    </div>
                    <button onclick="openCaseStudyModal('${c.id}')" class="w-full py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-xs font-semibold">Read Case Study</button>
                </div>
            `;
            csGrid.appendChild(card);
        });

        // Testimonials
        const tGrid = document.getElementById('testimonialsGrid');
        const dbTestimonials = <?= json_encode($db_testimonials) ?>;
        const activeTestimonials = dbTestimonials.length > 0 ? dbTestimonials : TESTIMONIALS;
        activeTestimonials.forEach(t => {
            const card = document.createElement('div');
            card.className = "glass p-6 rounded-2xl border border-gray-800 flex flex-col justify-between";
            card.innerHTML = `
                <div>
                    <div class="flex items-center space-x-1 mb-4 text-amber-500 text-xs">
                        ${Array(t.star_rating || t.stars || 5).fill('<i class="fa-solid fa-star"></i>').join('')}
                    </div>
                    <p class="text-sm text-gray-300 italic mb-6">"${t.feedback}"</p>
                </div>
                <div class="border-t border-gray-800 pt-4">
                    <span class="block text-sm font-bold text-white">${t.client_name || t.name}</span>
                    <span class="text-[11px] text-gray-400 block">${t.client_role || t.role}</span>
                </div>
            `;
            tGrid.appendChild(card);
        });

        // Blogs
        const bGrid = document.getElementById('blogGrid');
        const dbBlogs = <?= json_encode($db_blogs) ?>;
        const activeBlogs = dbBlogs.length > 0 ? dbBlogs : BLOG_ARTICLES;
        activeBlogs.forEach(b => {
            const card = document.createElement('div');
            card.className = "glass rounded-2xl border border-gray-800/80 overflow-hidden flex flex-col justify-between p-4 glow-hover";
            card.innerHTML = `
                <div>
                    <img src="${b.featured_image || b.img}" class="w-full h-40 object-cover rounded-lg mb-4">
                    <h4 class="text-base font-bold text-white mb-2 line-clamp-2">${b.title}</h4>
                    <p class="text-xs text-gray-400 line-clamp-3">${b.summary || b.desc}</p>
                </div>
                <div class="pt-4">
                    <button onclick="openBlogModal('${b.id || b.slug}')" class="w-full py-2 bg-gray-950 border border-gray-850 hover:bg-gray-900 text-brand-500 rounded-lg text-xs font-semibold">Read Article</button>
                </div>
            `;
            bGrid.appendChild(card);
        });

        // FAQ
        const faqContainer = document.getElementById('faqContainer');
        FAQS.forEach((faq, index) => {
            const div = document.createElement('div');
            div.className = "glass rounded-xl border border-gray-800/80 overflow-hidden";
            div.innerHTML = `
                <button onclick="toggleFaq(${index})" class="w-full px-6 py-4 text-left flex items-center justify-between text-white font-medium hover:bg-slate-900/40 transition-colors">
                    <span class="text-sm pr-4">${faq.q}</span>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300" id="faqIcon-${index}"></i>
                </button>
                <div class="hidden px-6 pb-5 text-xs text-gray-400 leading-relaxed border-t border-gray-900 pt-3" id="faqAnswer-${index}">
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
        // UNIFIED MODAL INTERACTIONS (Populates all details on request)
        // -------------------------------------------------------------
        const modal = document.getElementById('unifiedModal');
        const modalIconBox = document.getElementById('modalIconBox');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');

        function openUnifiedModal() { modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
        function closeUnifiedModal() { modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }

        window.openServiceModal = function(id) {
            const s = activeServices.find(srv => srv.id === id || srv.service_key === id);
            if (!s) return;

            modalIconBox.innerHTML = `<i class="${s.icon || 'fa-solid fa-cube'} text-xl"></i>`;
            modalTitle.textContent = s.title;
            modalBody.innerHTML = `
                <p class="text-sm leading-relaxed">${s.description || s.desc}</p>
                <h5 class="font-bold text-white text-xs uppercase tracking-wider mt-4">Why Businesses Need It</h5>
                <p class="text-gray-400 text-xs leading-relaxed">${s.whyNeeded || 'Essential strategic development execution.'}</p>
                <h5 class="font-bold text-white text-xs uppercase tracking-wider mt-4">Common Problems Resolved</h5>
                <p class="text-gray-400 text-xs leading-relaxed">${s.problems || 'Complex system bottlenecks, broken layouts.'}</p>
                <h5 class="font-bold text-white text-xs uppercase tracking-wider mt-4">Expected Structural Outcome</h5>
                <p class="text-gray-400 text-xs leading-relaxed">${s.results || 'Higher speed index and conversion rate optimization.'}</p>
            `;
            openUnifiedModal();
        }

        window.openShopifyModal = function(id) {
            const item = SHOPIFY_EXPERT_ITEMS.find(s => s.id === id);
            if (!item) return;

            modalIconBox.innerHTML = `<i class="${item.icon} text-xl"></i>`;
            modalTitle.textContent = item.title;
            modalBody.innerHTML = `
                <p class="leading-relaxed">${item.desc}</p>
                <p class="text-xs text-gray-400 mt-4 leading-relaxed">As certified partners, we configure these baseline parameters directly inside your store settings for immediate activation.</p>
            `;
            openUnifiedModal();
        }

        window.openCaseStudyModal = function(id) {
            const c = CASE_STUDIES.find(cs => cs.id === id);
            if (!c) return;

            modalIconBox.innerHTML = `<i class="fa-solid fa-square-poll-horizontal text-xl"></i>`;
            modalTitle.textContent = c.title;
            modalBody.innerHTML = `
                <p class="text-sm leading-relaxed">${c.prose}</p>
                <div class="p-4 bg-gray-950 border border-gray-850 rounded-xl mt-4">
                    <span class="text-xs text-brand-500 font-bold block">${c.results}</span>
                </div>
            `;
            openUnifiedModal();
        }

        window.openBlogModal = function(id) {
            const b = activeBlogs.find(art => art.id === id || art.slug === id);
            if (!b) return;

            modalIconBox.innerHTML = `<i class="fa-regular fa-newspaper text-xl"></i>`;
            modalTitle.textContent = b.title;
            modalBody.innerHTML = `
                <div class="space-y-4">
                    <img src="${b.featured_image || b.img}" class="w-full h-48 object-cover rounded-xl mb-4">
                    <div class="prose prose-invert text-sm leading-relaxed">${b.content}</div>
                </div>
            `;
            openUnifiedModal();
        }

        const LEGAL_DOCS = {
            privacy: { title: "Privacy Policy", body: "<p>We prioritize your secure information. Any diagnostic credentials, client records, or visit counts stay private and protected.</p>" },
            terms: { title: "Terms & Conditions", body: "<p>Project builds and payment processing are delivered based on client-approved scopes and timelines.</p>" }
        };

        window.openLegalModal = function(docKey) {
            const doc = LEGAL_DOCS[docKey];
            if (!doc) return;

            modalIconBox.innerHTML = `<i class="fa-solid fa-gavel text-xl"></i>`;
            modalTitle.textContent = doc.title;
            modalBody.innerHTML = doc.body;
            openUnifiedModal();
        }

        // -------------------------------------------------------------
        // AJAX Form Handling
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
<?php
require_once 'db_config.php';

$success_insert = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات وتطهيرها بشكل أولي
    $first_name = strip_tags(trim($_POST['first_name'] ?? ''));
    $last_name = strip_tags(trim($_POST['last_name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST['phone'] ?? ''));
    $interest = strip_tags(trim($_POST['interest'] ?? ''));
    $current_level = strip_tags(trim($_POST['current_level'] ?? ''));
    $notes = strip_tags(trim($_POST['notes'] ?? ''));

    // التحقق من الحقول الإجبارية
    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone) && !empty($interest)) {
        try {
            $sql = "INSERT INTO students (first_name, last_name, email, phone, interest, current_level, notes) 
                    VALUES (:first_name, :last_name, :email, :phone, :interest, :current_level, :notes)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':phone' => $phone,
                ':interest' => $interest,
                ':current_level' => $current_level,
                ':notes' => $notes
            ]);

            $success_insert = true;
        } catch (PDOException $e) {
            echo "<script>alert('حدث خطأ أثناء حفظ البيانات: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NexTech Academy | أكاديمية نكستك</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Cairo', 'sans-serif'],
          },
          colors: {
            primary: '#0f172a',
            secondary: '#06b6d4',
            accent: '#f59e0b',
            dark: '#020617',
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'spin-slow': 'spin 8s linear infinite',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-20px)' },
            }
          }
        }
      }
    }
  </script>
  <style>
    * {
      scroll-behavior: smooth;
    }
    
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #0f172a;
      color: #e2e8f0;
      overflow-x: hidden;
    }

    .glass {
      background: rgba(15, 23, 42, 0.7);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .glass-card {
      background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(6, 182, 212, 0.2);
      transition: all 0.3s ease;
    }

    .glass-card:hover {
      transform: translateY(-5px);
      border-color: rgba(245, 158, 11, 0.5);
      box-shadow: 0 20px 40px rgba(6, 182, 212, 0.2);
    }

    .gradient-text {
      background: linear-gradient(135deg, #06b6d4 0%, #f59e0b 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-gradient {
      background: radial-gradient(circle at top right, rgba(6, 182, 212, 0.3) 0%, transparent 50%),
                  radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.2) 0%, transparent 50%);
    }

    .grid-pattern {
      background-image: radial-gradient(circle, rgba(6, 182, 212, 0.1) 1px, transparent 1px);
      background-size: 40px 40px;
    }

    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease-out;
    }

    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }

    .nav-link {
      position: relative;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -4px;
      right: 0;
      background: linear-gradient(90deg, #06b6d4, #f59e0b);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .course-card {
      background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
      border: 1px solid rgba(148, 163, 184, 0.1);
    }

    .gold-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #f59e0b 100%);
    }

    #scrollBar {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      width: 0%;
      background: linear-gradient(90deg, #06b6d4, #f59e0b);
      z-index: 9999;
      transition: width 0.1s linear;
    }
  </style>
</head>
<body class="antialiased">

    <!-- Scroll Progress Bar -->
    <div id="scrollBar"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="logo.png" alt="NexTech Academy" class="w-12 h-12 object-contain rounded-full">
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">NexTech</h1>
                        <span class="text-xs text-gray-400 block -mt-1">أكاديمية</span>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home" class="nav-link text-gray-300 hover:text-white transition">الرئيسية</a>
                    <a href="#courses" class="nav-link text-gray-300 hover:text-white transition">الدورات</a>
                    <a href="#services" class="nav-link text-gray-300 hover:text-white transition">الخدمات</a>
                    <a href="#register" class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-full text-white font-semibold hover:shadow-lg hover:shadow-cyan-500/30 transition">سجّل الآن</a>
                </div>

                <button class="md:hidden text-2xl text-white" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden glass border-t border-gray-800" id="mobile-menu">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#home" class="block py-3 text-gray-300 hover:text-cyan-400 transition">الرئيسية</a>
                <a href="#courses" class="block py-3 text-gray-300 hover:text-cyan-400 transition">الدورات</a>
                <a href="#services" class="block py-3 text-gray-300 hover:text-cyan-400 transition">الخدمات</a>
                <a href="#register" class="block py-3 text-cyan-400 font-bold">سجّل الآن</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-20 px-4 sm:px-6 lg:px-8 hero-gradient overflow-hidden">
        <div class="grid-pattern absolute inset-0 opacity-30"></div>
        <div class="max-w-7xl mx-auto w-full relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="reveal">
                    <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-full">
                        <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-cyan-400">أكاديمية التقنية والتطوير</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        ابنِ مستقبلك في
                        <span class="gradient-text">عالم التقنية</span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        دورات احترافية في تطوير المواقع، تصميم واجهات المستخدم، والأتمتة الذكية — مع خدمات مخصصة للأفراد والشركات.
                    </p>
                    <div class="flex gap-4 mb-12">
                        <a href="#courses" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-lg text-white font-bold hover:shadow-lg hover:shadow-cyan-500/50 transition">
                            استعرض الدورات
                        </a>
                        <a href="#register" class="px-8 py-3 border border-gray-500 rounded-lg text-white font-bold hover:border-cyan-400 hover:text-cyan-400 transition">
                            سجّل الآن
                        </a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="glass-card rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold gradient-text">500+</div>
                            <div class="text-xs text-gray-400 mt-2">طالب</div>
                        </div>
                        <div class="glass-card rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold gradient-text">3+</div>
                            <div class="text-xs text-gray-400 mt-2">مسارات</div>
                        </div>
                        <div class="glass-card rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold gradient-text">15+</div>
                            <div class="text-xs text-gray-400 mt-2">خدمة</div>
                        </div>
                        <div class="glass-card rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold gradient-text">96%</div>
                            <div class="text-xs text-gray-400 mt-2">رضا</div>
                        </div>
                    </div>
                </div>

                <div class="reveal hidden md:block">
                    <div class="relative w-80 h-80 mx-auto">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-amber-500/20 rounded-full blur-3xl"></div>
                        <img src="logo.png" alt="NexTech" class="relative w-full h-full object-contain animate-float">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-full text-cyan-400 text-sm font-semibold mb-4"><svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> مساراتنا التعليمية</span>
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    دوراتنا <span class="gradient-text">التدريبية</span>
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto">ثلاثة مسارات متخصصة مصمَّمة لمنحك المهارات الأكثر طلباً في سوق العمل الرقمي</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Course 1 -->
                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center mb-4">
                        <i class="fas fa-globe text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">تطوير المواقع</h3>
                    <p class="text-gray-300 mb-4">أتقن بناء مواقع ويب احترافية من الصفر باستخدام HTML، CSS، JavaScript، والفريموركس الحديثة.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">HTML/CSS</span>
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">JavaScript</span>
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">React</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-400 font-bold">7900 DA</span>
                        <a href="#register" class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition text-sm font-semibold">سجّل</a>
                    </div>
                </div>

                <!-- Course 2 -->
                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center mb-4">
                        <i class="fas fa-palette text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">UI/UX Design</h3>
                    <p class="text-gray-300 mb-4">تعلّم أسس التصميم البصري، تجربة المستخدم، والنماذج الأولية باستخدام Figma والأدوات الاحترافية.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="text-xs px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full">Figma</span>
                        <span class="text-xs px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full">Design</span>
                        <span class="text-xs px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full">Prototyping</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-400 font-bold">6500 DA</span>
                        <a href="#register" class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition text-sm font-semibold">سجّل</a>
                    </div>
                </div>

                <!-- Course 3 -->
                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center mb-4">
                        <i class="fas fa-robot text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">الأتمتة الذكية</h3>
                    <p class="text-gray-300 mb-4">أتمتة سير العمل وزيادة الإنتاجية باستخدام التطبيقات الذكية والروبوتات. حلول عملية للشركات.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">Automation</span>
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">Bots</span>
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">AI</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-400 font-bold">8500 DA</span>
                        <a href="#register" class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition text-sm font-semibold">سجّل</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-2 bg-amber-500/10 border border-amber-500/30 rounded-full text-amber-400 text-sm font-semibold mb-4"><svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg> خدماتنا المتخصصة</span>
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    حلول <span class="gradient-text">للشركات والأفراد</span>
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto">نقدم خدمات احترافية مخصصة لتحويل أفكارك إلى واقع رقمي</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-globe text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">إنشاء المواقع</h3>
                    </div>
                    <p class="text-gray-300">مواقع ويب احترافية وسريعة مع أفضل ممارسات SEO لضمان ظهورك في نتائج البحث.</p>
                </div>

                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                            <i class="fas fa-palette text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">التصميم المخصص</h3>
                    </div>
                    <p class="text-gray-300">تصاميم فريدة وجذابة تعكس هوية علامتك التجارية وتجذب جمهورك المستهدف.</p>
                </div>

                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center">
                            <i class="fas fa-robot text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">أتمتة الأعمال</h3>
                    </div>
                    <p class="text-gray-300">أتمتة العمليات المتكررة وزيادة الإنتاجية من خلال أنظمة ذكية مخصصة.</p>
                </div>

                <div class="glass-card rounded-2xl p-8 reveal">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-image text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">الجرافيك ديزاين</h3>
                    </div>
                    <p class="text-gray-300">محتوى بصري احترافي: لوجوهات، منشورات، ومواد تسويقية عالية الجودة.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 reveal">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    سجّل في <span class="gradient-text">NexTech Academy</span>
                </h2>
                <p class="text-gray-400">انضم إلى مئات الطلاب الذين غيروا مسارهم الوظيفي</p>
            </div>

            <div class="glass-card rounded-2xl p-8 md:p-12">
                <?php if ($success_insert): ?>
                    <div class="mb-8 p-6 bg-green-500/20 border border-green-500/50 rounded-lg text-center">
                        <div class="flex justify-center mb-3"><svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <h3 class="text-2xl font-bold text-white mb-2">تم استلام طلبك بنجاح!</h3>
                        <p class="text-gray-300">شكراً لاهتمامك بـ NexTech Academy. سيتواصل معك فريقنا خلال 24 ساعة.</p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-2">الاسم الأول *</label>
                            <input type="text" name="first_name" placeholder="أدخل اسمك الأول" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">اسم العائلة *</label>
                            <input type="text" name="last_name" placeholder="أدخل اسم العائلة" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-2">البريد الإلكتروني *</label>
                            <input type="email" name="email" placeholder="example@email.com" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">رقم الهاتف (واتساب) *</label>
                            <input type="tel" name="phone" placeholder="+213 5X XXX XXXX" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">ما الذي يهمك؟ *</label>
                        <select name="interest" required class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition">
                            <option value="">-- اختر ما يناسبك --</option>
                            <option value="دورة تطوير المواقع">دورة تطوير المواقع</option>
                            <option value="دورة UI/UX والجرافيك ديزاين">دورة UI/UX والجرافيك ديزاين</option>
                            <option value="دورة الأتمتة Automation">دورة الأتمتة Automation</option>
                            <option value="إنشاء موقع ويب للشركة">إنشاء موقع ويب للشركة</option>
                            <option value="تصميم حسب الطلب">تصميم حسب الطلب</option>
                            <option value="أتمتة الأعمال">أتمتة الأعمال</option>
                            <option value="خدمات الجرافيك ديزاين">خدمات الجرافيك ديزاين</option>
                            <option value="أرغب في الاستشارة أولاً">أرغب في الاستشارة أولاً</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">مستواك الحالي</label>
                        <select name="current_level" class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition">
                            <option value="">-- مستواك في المجال --</option>
                            <option value="مبتدئ تماماً">مبتدئ تماماً</option>
                            <option value="لديّ بعض الأساسيات">لديّ بعض الأساسيات</option>
                            <option value="متوسط">متوسط</option>
                            <option value="متقدم أبحث عن تخصص">متقدم أبحث عن تخصص</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">ملاحظات أو أسئلة إضافية</label>
                        <textarea name="notes" placeholder="أخبرنا عن هدفك أو أي استفسار لديك..." rows="4"
                            class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white focus:border-cyan-500 focus:outline-none transition"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl text-white font-bold hover:shadow-lg hover:shadow-cyan-500/50 transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        أرسل طلب التسجيل
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="logo.png" alt="NexTech Academy" class="w-12 h-12 object-contain rounded-full">
                        <h3 class="text-2xl font-bold gradient-text">NexTech Academy</h3>
                    </div>
                    <p class="text-gray-400 max-w-sm mb-6">أكاديمية تقنية حديثة تهدف إلى تمكين الشباب من اكتساب المهارات الرقمية المطلوبة في سوق العمل الحديث.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-cyan-500 hover:text-white transition text-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/nextech__academy/" target="_blank" class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-cyan-500 hover:text-white transition text-sm" >
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#home" class="hover:text-cyan-400 transition">الرئيسية</a></li>
                        <li><a href="#courses" class="hover:text-cyan-400 transition">الدورات</a></li>
                        <li><a href="#services" class="hover:text-cyan-400 transition">الخدمات</a></li>
                        <li><a href="#register" class="hover:text-cyan-400 transition">التسجيل</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">تواصل معنا</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> nextechacademy26@gmail.com</li>
                        <li class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> +213 5X XXX XXXX</li>
                        <li class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> الجزائر</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">© 2025 NexTech Academy. جميع الحقوق محفوظة.</p>
                <div class="flex gap-6 text-sm text-gray-500">
                    <a href="#" class="hover:text-gray-300 transition">سياسة الخصوصية</a>
                    <a href="#" class="hover:text-gray-300 transition">شروط الاستخدام</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Scroll progress bar
        window.addEventListener('scroll', () => {
            const scrollBar = document.getElementById('scrollBar');
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = (scrollTop / docHeight) * 100;
            scrollBar.style.width = scrolled + '%';
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Reveal animation
        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });

        revealEls.forEach(el => revealObserver.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
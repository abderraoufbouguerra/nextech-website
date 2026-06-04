<?php
require_once 'db_config.php';

$success_insert = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات وتطهيرها بشكل أولي
    $first_name = strip_tags(trim($_POST['first_name'] ?? ''));
    $last_name = strip_tags(trim($_POST['last_name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST['phone'] ?? ''));
    $country = strip_tags(trim($_POST['country'] ?? ''));
    $interest = strip_tags(trim($_POST['interest'] ?? ''));
    $current_level = strip_tags(trim($_POST['current_level'] ?? ''));
    $learning_method = strip_tags(trim($_POST['learning_method'] ?? ''));
    $notes = strip_tags(trim($_POST['notes'] ?? ''));

    // التحقق من الحقول الإجبارية
    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone) && !empty($interest)) {
        try {
            $sql = "INSERT INTO students (first_name, last_name, email, phone, country, interest, current_level, learning_method, notes) 
                    VALUES (:first_name, :last_name, :email, :phone, :country, :interest, :current_level, :learning_method, :notes)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':phone' => $phone,
                ':country' => $country,
                ':interest' => $interest,
                ':current_level' => $current_level,
                ':learning_method' => $learning_method,
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
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ── SCROLL PROGRESS ── -->
<div id="scrollBar"></div>

<!-- ══════════════════════════════════════
     NAVBAR
══════════════════════════════════════ -->
<nav class="navbar">
  <a href="#home" class="nav-logo">
    <!-- استبدل "logo.png" باسم ملف الشعار الخاص بك -->
    <img src="logo.png.webp" alt="NexTech Academy Logo">
    <div class="nav-logo-text">
      <span class="brand">NexTech</span>
      <span class="sub">Academy</span>
    </div>
  </a>

  <ul class="nav-links">
    <li><a href="#home">الرئيسية</a></li>
    <li><a href="#courses">الدورات</a></li>
    <li><a href="#services">خدماتنا</a></li>
    <li><a href="#why">لماذا نحن</a></li>
    <li><a href="#register" class="nav-cta">سجّل الآن</a></li>
  </ul>

  <div class="hamburger" id="hamburger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</nav>

<!-- Mobile nav -->
<div class="mobile-nav" id="mobileNav">
  <a href="#home"     onclick="toggleMenu()">🏠 الرئيسية</a>
  <a href="#courses"  onclick="toggleMenu()">📚 الدورات</a>
  <a href="#services" onclick="toggleMenu()">⚡ خدماتنا</a>
  <a href="#why"      onclick="toggleMenu()">✨ لماذا نحن</a>
  <a href="#register" onclick="toggleMenu()">📋 سجّل الآن</a>
</div>

<!-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ -->
<section class="hero" id="home">
  <div class="hero-grid"></div>
  <div class="hero-orb orb-1"></div>
  <div class="hero-orb orb-2"></div>

  <div class="hero-inner">
    <!-- Content -->
    <div class="hero-content">
      <div class="hero-eyebrow">
        <span class="dot"></span>
        أكاديمية التكنولوجيا والتطوير الرقمي
      </div>

      <h1>
        ابنِ مستقبلك في
        <span class="line-teal">عالم التقنية</span>
        <span class="line-gold">مع NexTech Academy</span>
      </h1>

      <p class="hero-desc">
        نُقدّم دورات احترافية في تطوير المواقع، تصميم واجهات المستخدم،
        والأتمتة الذكية — مع خدمات مخصصة للأفراد والشركات.
      </p>

      <div class="hero-actions">
        <a href="#courses" class="btn-hero-primary">
          🚀 استعرض الدورات
        </a>
        <a href="#register" class="btn-hero-outline">
          📋 سجّل 
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat-item">
          <span class="stat-num">+500</span>
          <div class="stat-label">زائر للأكاديمية </div>
        </div>
        <div class="stat-item">
          <span class="stat-num">3</span>
          <div class="stat-label">مسارات متخصصة</div>
        </div>
        <div class="stat-item">
          <span class="stat-num">15+</span>
          <div class="stat-label">خدمة رقمية</div>
        </div>
        <div class="stat-item">
          <span class="stat-num">96%</span>
          <div class="stat-label">رضا الطلاب</div>
        </div>
      </div>
    </div>

    <!-- Logo Visual -->
    <div class="hero-visual">
      <div class="hero-visual-ring"></div>
      <!-- استبدل "logo.png" باسم ملف الشعار -->
      <img src="logo.pn.g.png" alt="NexTech Academy">
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     COURSES
══════════════════════════════════════ -->
<section id="courses">
  <div class="section-header reveal">
    <span class="section-tag tag-teal">📚 مساراتنا التعليمية</span>
    <h2 class="section-title">
      دوراتنا <span class="accent-gold">التدريبية</span>
    </h2>
    <div class="divider-line"></div>
    <p class="section-sub">
      ثلاثة مسارات متخصصة مصمَّمة لمنحك المهارات الأكثر طلباً في سوق العمل الرقمي
    </p>
  </div>

  <div class="courses-grid">

    <!-- Card 1: Web Development -->
    <div class="course-card reveal">
      <div class="course-top ct-web">
        <div class="course-top-glow"></div>
        <div class="course-icon-wrap ci-teal">🌐</div>
        <span class="course-badge badge-hot">الأكثر طلباً</span>
      </div>
      <div class="course-body">
        <h3>تطوير المواقع الإلكترونية</h3>
        <p>
          أتقن بناء مواقع ويب احترافية من الصفر باستخدام HTML، CSS، JavaScript،
          وأحدث الفريموركس. ستخرج بمشاريع حقيقية جاهزة للنشر.
        </p>
        <div class="course-tags">
          <span class="course-tag">HTML / CSS</span>
          <span class="course-tag">JavaScript</span>
          <span class="course-tag">React</span>
          <span class="course-tag">php</span>
        </div>
        <div class="course-footer">
          <div class="course-meta-info">
            <span class="course-price">💰 7900 DA</span>
            <span class="course-duration">⏱ 48 ساعة تدريبية</span>
          </div>
          <button class="btn-course bc-teal" onclick="scrollToForm()">
            سجّل الآن ←
          </button>
        </div>
      </div>
    </div>

    <!-- Card 2: UI/UX Design -->
    <div class="course-card reveal">
      <div class="course-top ct-ui">
        <div class="course-top-glow"></div>
        <div class="course-icon-wrap ci-gold">🎨</div>
        <span class="course-badge badge-new">جديد</span>
      </div>
      <div class="course-body">
        <h3>واجهة المستخدم وتجربته UI/UX</h3>
        <p>
          تعلّم أسس التصميم البصري، تجربة المستخدم، والنماذج الأولية باستخدام
          Figma والأدوات الاحترافية. حوّل أفكارك إلى تصاميم جذابة وقابلة للتنفيذ.
        </p>
        <div class="course-tags">
          <span class="course-tag">Figma</span>
          <span class="course-tag">Design Systems</span>
          <span class="course-tag">Prototyping</span>
          <span class="course-tag">Graphic Design</span>
        </div>
        <div class="course-footer">
          <div class="course-meta-info">
            <span class="course-price">💰 4900 DA</span>
            <span class="course-duration">⏱ 40 ساعة تدريبية</span>
          </div>
          <button class="btn-course bc-gold" onclick="scrollToForm()">
            سجّل الآن ←
          </button>
        </div>
      </div>
    </div>

    <!-- Card 3: Automation -->
    <div class="course-card reveal">
      <div class="course-top ct-auto">
        <div class="course-top-glow"></div>
        <div class="course-icon-wrap ci-auto">⚙️</div>
        <span class="course-badge badge-pro">احترافي</span>
      </div>
      <div class="course-body">
        <h3>الأتمتة الذكية Automation</h3>
        <p>
          أتمتة المهام المتكررة وبناء سير عمل تلقائية للشركات والأفراد باستخدام
          Make، Zapier، وPython. وفّر الوقت وضاعف الإنتاجية.
        </p>
        <div class="course-tags">
          <span class="course-tag">Make (Integromat)</span>
          <span class="course-tag">Zapier</span>
          <span class="course-tag">Python</span>w
          <span class="course-tag">API Integration</span>
        </div>
        <div class="course-footer">
          <div class="course-meta-info">
            <span class="course-price">💰 7900 DA</span>
            <span class="course-duration">⏱ 36 ساعة تدريبية</span>
          </div>
          <button class="btn-course bc-green" onclick="scrollToForm()">
            سجّل الآن ←
          </button>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════
     SERVICES
══════════════════════════════════════ -->
<section id="services">
  <div class="section-header reveal">
    <span class="section-tag tag-teal">⚡ ما نقدمه لعملائنا</span>
    <h2 class="section-title">
      خدماتنا <span class="accent-gold">الاحترافية</span>
    </h2>
    <div class="divider-line"></div>
    <p class="section-sub">
      نحوّل أفكار الشركات والأفراد إلى حلول رقمية متكاملة — من التصميم حتى الأتمتة
    </p>
  </div>

  <div class="services-layout">

    <!-- Featured: Web Dev for Companies -->
    <div class="service-card featured reveal">
      <div class="service-icon-box sib-white">🏢</div>
      <div class="service-content">
        <h3>إنشاء مواقع ويب للشركات</h3>
        <p>
          نصمّم ونطوّر مواقع إلكترونية احترافية وسريعة وآمنة لشركتك — من الموقع التعريفي
          البسيط إلى متاجر التجارة الإلكترونية المتكاملة. كل موقع يُبنى وفق هويتك البصرية
          ومتطلباتك التقنية الخاصة.
        </p>
        <ul class="service-features">
          <li>تصميم متجاوب مع كل الأجهزة</li>
          <li>تحسين محركات البحث SEO</li>
          <li>لوحة تحكم سهلة الاستخدام</li>
          <li>دعم فني مستمر بعد الإطلاق</li>
          <li>سرعة تحميل عالية وأمان متقدم</li>
        </ul>
      </div>
    </div>

    <!-- Service 2: UI Design on demand -->
    <div class="service-card reveal">
      <div class="service-icon-box sib-gold">🎨</div>
      <div class="service-content">
        <h3>تصميم حسب الطلب</h3>
        <p>
          سواء احتجت شعاراً، هوية بصرية كاملة، تصميم تطبيق، أو موشن جرافيك —
          فريقنا يُنجز طلبك باحترافية وفق رؤيتك.
        </p>
        <ul class="service-features">
          <li>هوية بصرية وبراندينج</li>
          <li>تصميم UI/UX للتطبيقات</li>
          <li>مواد تسويقية وسوشيال ميديا</li>
          <li>موشن جرافيك وإنفوجرافيك</li>
        </ul>
      </div>
    </div>

    <!-- Service 3: Automation for companies -->
    <div class="service-card reveal">
      <div class="service-icon-box sib-green">⚙️</div>
      <div class="service-content">
        <h3>أتمتة الأعمال للشركات</h3>
        <p>
          نُحلّل سير عمل شركتك ونبني لك أنظمة أتمتة ذكية تُقلّل التكاليف،
          تُسرّع الإنجاز، وتُلغي المهام المتكررة.
        </p>
        <ul class="service-features">
          <li>أتمتة العمليات الداخلية</li>
          <li>ربط الأنظمة والتطبيقات</li>
          <li>تقارير وتنبيهات تلقائية</li>
          <li>دمج الذكاء الاصطناعي في العمليات</li>
        </ul>
      </div>
    </div>

    <!-- Service 4: Graphic Design -->
    <div class="service-card reveal">
      <div class="service-icon-box sib-teal">✏️</div>
      <div class="service-content">
        <h3>الجرافيك ديزاين الاحترافي</h3>
        <p>
          نصنع محتوى بصرياً يجذب الانتباه ويعزّز حضورك الرقمي على وسائل التواصل
          الاجتماعي والإعلانات والمطبوعات.
        </p>
        <ul class="service-features">
          <li>تصميم سوشيال ميديا يومي</li>
          <li>إعلانات رقمية ومطبوعة</li>
          <li>تصميم مواد تسويقية</li>
          <li>تعديل وتحرير الصور</li>
        </ul>
      </div>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════
     WHY US
══════════════════════════════════════ -->
<section class="why-section" id="why">
  <div class="section-header why-header reveal">
    <span class="section-tag tag-white">✨ مزايانا</span>
    <h2 class="section-title light">
      لماذا تختار <span class="accent-gold">NexTech؟</span>
    </h2>
    <div class="divider-line"></div>
    <p class="section-sub light">نؤمن بأن الجودة تصنع الفارق — في التعليم والخدمات على حدٍّ سواء</p>
  </div>

  <div class="why-grid">
    <div class="why-card reveal" data-num="01">
      <div class="why-icon">🎓</div>
      <h4> مدرّبون بخبرات  </h4>
      <p>فريق من المتخصصين بخبرات حقيقية — يُدرّسون ما طبّقوه بأنفسهم.</p>
    </div>
    <div class="why-card reveal" data-num="02">
      <div class="why-icon">⚡</div>
      <h4>تعلّم تطبيقي عملي 100%</h4>
      <p>لا نظريات فارغة — كل درس مرتبط بمشروع حقيقي تضعه في portfolio احترافي.</p>
    </div>
    
    <div class="why-card reveal" data-num="04">
      <div class="why-icon">🤝</div>
      <h4>دعم مستمر 24/7</h4>
      <p>مجتمع طلابي نشط + طاقم دعم متاح دائماً للإجابة عن كل أسئلتك.</p>
    </div>
    <div class="why-card reveal" data-num="05">
      <div class="why-icon">🔄</div>
      <h4>محتوى محدَّث باستمرار</h4>
      <p>نواكب التطورات التقنية السريعة — مناهجنا تتجدد كل 3 أشهر.</p>
    </div>
   
  </div>
</section>

<!-- ══════════════════════════════════════
     REGISTRATION FORM
══════════════════════════════════════ -->
<section id="register">
  <div class="section-header reveal">
    <span class="section-tag tag-teal">📋 انضم إلينا</span>
    <h2 class="section-title">
      سجّل <span class="accent-gold">الآن</span>
    </h2>
    <div class="divider-line"></div>
    <p class="section-sub">
      أدخل بياناتك وسيتواصل معك فريقنا خلال 24 ساعة لمساعدتك في اختيار المسار المناسب
    </p>
  </div>

  <div class="form-wrapper reveal">

    <!-- The Form -->
    <form id="regForm" method="POST" action="">
  <div class="form-grid">

    <div class="form-group">
      <label>الاسم الأول <span class="req">*</span></label>
      <input type="text" name="first_name" placeholder="أدخل اسمك الأول" required>
    </div>

    <div class="form-group">
      <label>اسم العائلة <span class="req">*</span></label>
      <input type="text" name="last_name" placeholder="أدخل اسم العائلة" required>
    </div>

    <div class="form-group">
      <label>البريد الإلكتروني <span class="req">*</span></label>
      <input type="email" name="email" placeholder="example@email.com" required>
    </div>

    <div class="form-group">
      <label>رقم الهاتف (واتساب) <span class="req">*</span></label>
      <input type="text" name="phone" placeholder="+213 5X XXX XXXX" required>
    </div>

    <div class="form-group">
      <label>الدولة</label>
      <select name="country">
        <option value="">-- اختر دولتك --</option>
        <option value="المملكة العربية السعودية">المملكة العربية السعودية</option>
        <option value="الإمارات العربية المتحدة">الإمارات العربية المتحدة</option>
        <option value="الكويت">الكويت</option>
        <option value="قطر">قطر</option>
        <option value="البحرين">البحرين</option>
        <option value="عُمان">عُمان</option>
        <option value="مصر">مصر</option>
        <option value="الأردن">الأردن</option>
        <option value="المغرب">المغرب</option>
        <option value="تونس">تونس</option>
        <option value="الجزائر">الجزائر</option>
        <option value="دولة أخرى">دولة أخرى</option>
      </select>
    </div>

    <div class="form-group">
      <label>ما الذي يهمك؟ <span class="req">*</span></label>
      <select name="interest" required>
        <option value="">-- اختر ما يناسبك --</option>
        <optgroup label="الدورات التدريبية">
          <option value="دورة تطوير المواقع">دورة تطوير المواقع</option>
          <option value="دورة UI/UX والجرافيك ديزاين">دورة UI/UX والجرافيك ديزاين</option>
          <option value="دورة الأتمتة Automation">دورة الأتمتة Automation</option>
        </optgroup>
        <optgroup label="الخدمات للشركات">
          <option value="إنشاء موقع ويب للشركة">إنشاء موقع ويب للشركة</option>
          <option value="تصميم حسب الطلب">تصميم حسب الطلب</option>
          <option value="أتمتة الأعمال">أتمتة الأعمال</option>
          <option value="خدمات الجرافيك ديزاين">خدمات الجرافيك ديزاين</option>
        </optgroup>
        <option value="أرغب في الاستشارة أولاً">أرغب في الاستشارة أولاً</option>
      </select>
    </div>

    <div class="form-group">
      <label>مستواك الحالي</label>
      <select name="current_level">
        <option value="">-- مستواك في المجال --</option>
        <option value="مبتدئ تماماً">مبتدئ تماماً</option>
        <option value="لديّ بعض الأساسيات">لديّ بعض الأساسيات</option>
        <option value="متوسط">متوسط</option>
        <option value="متقدم أبحث عن تخصص">متقدم أبحث عن تخصص</option>
      </select>
    </div>

    <div class="form-group">
      <label>طريقة التعلّم المفضلة</label>
      <select name="learning_method">
        <option value="">-- اختر الطريقة --</option>
        <option value="أونلاين مباشر (Live)">أونلاين مباشر (Live)</option>
        <option value="أونلاين مسجّل (مرن)">أونلاين مسجّل (مرن)</option>
        <option value="هجين (مسجّل + أونلاين)">هجين (مسجّل + أونلاين)</option>
      </select>
    </div>

    <div class="form-group f-full">
      <label>ملاحظات أو أسئلة إضافية</label>
      <textarea name="notes" placeholder="أخبرنا عن هدفك أو أي استفسار لديك..."></textarea>
    </div>

  </div>

  <div class="form-submit">
    <button type="submit" class="btn-submit">
      <span>🚀 أرسل طلب التسجيل</span>
    </button>
  </div>
</form>

    <!-- Success State -->
    <div class="success-state" id="successState">
      <div class="success-icon">✅</div>
      <h3>تم استلام طلبك بنجاح!</h3>
      <p>
        شكراً لاهتمامك بـ NexTech Academy.<br>
        سيتواصل معك فريقنا خلال 24 ساعة عبر البريد الإلكتروني أو واتساب.
      </p>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
<footer>
  <div class="footer-top">

    <!-- Brand -->
    <div class="footer-brand">
      <div class="footer-brand-logo">
        <img src="logo.png.webp" alt="NexTech">
        <div class="fb-text">
          <span class="b1">NexTech</span>
          <span class="b2">Academy</span>
        </div>
      </div>
      <p>أكاديمية NexTech — وجهتك الأولى لتعلم التقنية وبناء مسيرة مهنية ناجحة في العالم الرقمي.</p>
      <div class="socials">
        <a class="social-btn" href="#" title="تويتر / X" target="_blank">𝕏</a>
        <a class="social-btn" href="#" title="لينكدإن" target="_blank">in</a>
        <a class="social-btn" href="#" title="يوتيوب" target="_blank">▶</a>
        <a class="social-btn" href="https://www.instagram.com/nextech__academy?igsh=azh2YjFleGFuMDht" title="إنستجرام" target="_blank">📷</a>
        <a class="social-btn" href="#" title="واتساب" target="_blank">💬</a>
      </div>
    </div>

    <!-- Courses -->
    <div class="footer-col">
      <h4>الدورات</h4>
      <ul>
        <li><a href="#courses">🌐 تطوير المواقع</a></li>
        <li><a href="#courses">🎨 UI/UX والجرافيك</a></li>
        <li><a href="#courses">⚙️ الأتمتة Automation</a></li>
      </ul>
    </div>

    <!-- Services -->
    <div class="footer-col">
      <h4>الخدمات</h4>
      <ul>
        <li><a href="#services">مواقع للشركات</a></li>
        <li><a href="#services">تصميم حسب الطلب</a></li>
        <li><a href="#services">أتمتة الأعمال</a></li>
        <li><a href="#services">الجرافيك ديزاين</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div class="footer-col">
      <h4>تواصل معنا</h4>
      <ul class="footer-contact">
        <li>
          <span class="fc-icon">📧</span>
          <span>nextechacademy26@gmail.com</span>
        </li>
        <li>
          <span class="fc-icon">💬</span>
          <span>+213 5X XXX XXXX</span>
        </li>
        <li>
          <span class="fc-icon">📍</span>
          <span>الجزائر</span>
        </li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <span>© 2025 <span class="fb-logo">NexTech Academy</span>. جميع الحقوق محفوظة.</span>
    <span class="fb-heart">صُنِع بـ <span>❤️</span> للمتعلمين العرب</span>
  </div>
</footer>

<!-- ══════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════ -->
<script>
  /* ── Scroll Progress Bar ── */
  window.addEventListener('scroll', () => {
    const doc = document.documentElement;
    const pct = (window.scrollY / (doc.scrollHeight - doc.clientHeight)) * 100;
    document.getElementById('scrollBar').style.width = pct + '%';
  });

  /* ── Reveal on Scroll ── */
  const revealEls = document.querySelectorAll('.reveal');
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 90);
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 });
  revealEls.forEach(el => revealObserver.observe(el));

  /* ── Mobile Menu Toggle ── */
  let menuOpen = false;
  function toggleMenu() {
    menuOpen = !menuOpen;
    document.getElementById('mobileNav').classList.toggle('open', menuOpen);
    const bars = document.querySelectorAll('.hamburger span');
    if (menuOpen) {
      bars[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
      bars[1].style.opacity = '0';
      bars[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
    } else {
      bars.forEach(b => { b.style.transform = ''; b.style.opacity = ''; });
    }
  }

  /* ── Scroll to Form ── */
  function scrollToForm() {
    document.getElementById('register').scrollIntoView({ behavior: 'smooth' });
  }

  /* ── Form Submission ── */
 
  /* ── Close mobile menu on outside click ── */
  document.addEventListener('click', (e) => {
    if (menuOpen && !e.target.closest('.navbar') && !e.target.closest('.mobile-nav')) {
      toggleMenu();
    }
  });
</script>
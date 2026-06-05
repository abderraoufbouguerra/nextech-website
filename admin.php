<?php
require_once 'db_config.php';

// نظام حماية مبدئي (HTTP Authentication)
$admin_user = 'admin';
$admin_pass = 'NexTech@2026';

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $admin_user || $_SERVER['PHP_AUTH_PW'] !== $admin_pass) {
    header('WWW-Authenticate: Basic realm="NexTech Admin Dashboard"');
    header('HTTP/1.0 401 Unauthorized');
    die('الدخول غير مصرح به.');
}

// معالجة طلب الحذف
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
    $stmt->execute([':id' => $delete_id]);
    header("Location: admin.php");
    exit;
}

// جلب كل الطلاب
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();

// تجميع الطلاب حسب الاهتمام (الدورة)
$grouped = [];
foreach ($students as $s) {
    $grouped[$s['interest']][] = $s;
}

// إحصائيات
$total = count($students);
$courses_count = count($grouped);
$today = $pdo->query("SELECT COUNT(*) as count FROM students WHERE DATE(created_at) = CURDATE()")->fetch();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | NexTech Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                    colors: { primary: '#0f172a', secondary: '#06b6d4', accent: '#f59e0b' }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Cairo', sans-serif; }

        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #06b6d4 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.2);
        }

        /* Tabs */
        .tab-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 0.6rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid rgba(6, 182, 212, 0.2);
            background: rgba(15, 23, 42, 0.6);
            color: #94a3b8;
            transition: all 0.25s ease;
            white-space: nowrap;
        }
        .tab-btn:hover { border-color: rgba(6, 182, 212, 0.5); color: #e2e8f0; }
        .tab-btn.active {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.25) 0%, rgba(245, 158, 11, 0.15) 100%);
            border-color: #06b6d4;
            color: #06b6d4;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.2);
        }
        .tab-btn .badge {
            background: rgba(6, 182, 212, 0.2);
            color: #06b6d4;
            border-radius: 999px;
            padding: 0.1rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .tab-btn.active .badge {
            background: #06b6d4;
            color: #0f172a;
        }

        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* Table */
        table { border-collapse: separate; border-spacing: 0; width: 100%; }
        thead th {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(245, 158, 11, 0.1) 100%);
            border: 1px solid rgba(6, 182, 212, 0.2);
            padding: 0.85rem 1rem;
            text-align: right;
            font-weight: 700;
            color: #06b6d4;
            font-size: 0.85rem;
        }
        tbody td {
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.85rem 1rem;
            text-align: right;
            font-size: 0.85rem;
        }
        tbody tr { transition: background 0.2s; }
        tbody tr:hover { background: rgba(6, 182, 212, 0.1); }

        .btn-delete {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white; padding: 0.4rem 0.85rem; border-radius: 0.5rem;
            font-size: 0.8rem; border: none; cursor: pointer;
            transition: all 0.3s ease; text-decoration: none;
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
        }

        .no-data { text-align: center; padding: 3rem 1rem; color: #94a3b8; }

        /* Course card header colors */
        .course-header-0 { border-color: rgba(6, 182, 212, 0.4); }
        .course-header-1 { border-color: rgba(245, 158, 11, 0.4); }
        .course-header-2 { border-color: rgba(139, 92, 246, 0.4); }
        .course-header-3 { border-color: rgba(16, 185, 129, 0.4); }
        .course-header-4 { border-color: rgba(244, 63, 94, 0.4); }
        .course-header-5 { border-color: rgba(251, 146, 60, 0.4); }
        .course-header-6 { border-color: rgba(99, 102, 241, 0.4); }
        .course-header-7 { border-color: rgba(20, 184, 166, 0.4); }
    </style>
</head>
<body>

<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold gradient-text">لوحة التحكم</h1>
                    <p class="text-gray-400 text-sm">NexTech Academy - إدارة الطلبات والمسجلين</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="stat-card">
                    <div class="text-2xl md:text-3xl font-bold gradient-text mb-1"><?php echo $total; ?></div>
                    <div class="text-gray-400 text-sm">إجمالي المسجلين</div>
                </div>
                <div class="stat-card">
                    <div class="text-2xl md:text-3xl font-bold text-cyan-400 mb-1"><?php echo $courses_count; ?></div>
                    <div class="text-gray-400 text-sm">الدورات والخدمات</div>
                </div>
                <div class="stat-card">
                    <div class="text-2xl md:text-3xl font-bold text-emerald-400 mb-1"><?php echo $today['count']; ?></div>
                    <div class="text-gray-400 text-sm">المسجلين اليوم</div>
                </div>
            </div>
        </div>

        <?php if (empty($students)): ?>
            <!-- No data -->
            <div class="glass rounded-2xl p-12 no-data">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-lg">لا توجد طلبات تسجيل مسجلة حتى الآن.</p>
            </div>

        <?php else: ?>

            <!-- Tabs Navigation -->
            <div class="glass rounded-2xl p-6 mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <h2 class="text-lg font-bold text-white">تصفية حسب الدورة / الخدمة</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <!-- All tab -->
                    <button class="tab-btn active" onclick="switchTab('all', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        الكل
                        <span class="badge"><?php echo $total; ?></span>
                    </button>
                    <?php $i = 0; foreach ($grouped as $course => $members): ?>
                    <button class="tab-btn" onclick="switchTab('course-<?php echo $i; ?>', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <?php echo htmlspecialchars($course); ?>
                        <span class="badge"><?php echo count($members); ?></span>
                    </button>
                    <?php $i++; endforeach; ?>
                </div>
            </div>

            <!-- Tab: All -->
            <div id="tab-all" class="tab-panel active">
                <div class="glass rounded-2xl p-6 overflow-x-auto">
                    <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        جميع المسجلين
                        <span class="text-sm font-normal text-gray-400">(<?php echo $total; ?> طالب)</span>
                    </h2>
                    <?php echo renderTable($students, true); ?>
                </div>
            </div>

            <!-- Tabs: Per Course -->
            <?php $i = 0; foreach ($grouped as $course => $members): ?>
            <div id="tab-course-<?php echo $i; ?>" class="tab-panel">
                <div class="glass rounded-2xl p-6 overflow-x-auto border-r-4 course-header-<?php echo $i % 8; ?>">
                    <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <?php echo htmlspecialchars($course); ?>
                        <span class="text-sm font-normal text-gray-400">(<?php echo count($members); ?> طالب)</span>
                    </h2>
                    <?php echo renderTable($members, false); ?>
                </div>
            </div>
            <?php $i++; endforeach; ?>

        <?php endif; ?>

        <!-- Footer -->
        <div class="mt-12 text-center text-gray-500 text-sm">
            <p>© 2025 NexTech Academy. جميع الحقوق محفوظة.</p>
            <p class="mt-2">آخر تحديث: <span id="lastUpdate"></span></p>
        </div>
    </div>
</div>

<?php
function renderTable(array $rows, bool $showCourse): string {
    ob_start();
    ?>
    <table class="text-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>الاسم الكامل</th>
                <th>الإيميل</th>
                <th>الهاتف</th>
                <?php if ($showCourse): ?><th>الاهتمام</th><?php endif; ?>
                <th>المستوى</th>
                <th>التاريخ</th>
                <th>الإجراء</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $s): ?>
            <tr>
                <td>
                    <span class="px-3 py-1 bg-slate-800 rounded-full text-xs font-semibold text-cyan-400">
                        <?php echo htmlspecialchars($s['id']); ?>
                    </span>
                </td>
                <td class="text-white font-semibold">
                    <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?>
                </td>
                <td class="text-gray-300 text-xs">
                    <a href="mailto:<?php echo htmlspecialchars($s['email']); ?>" class="text-cyan-400 hover:underline">
                        <?php echo htmlspecialchars($s['email']); ?>
                    </a>
                </td>
                <td class="text-gray-300">
                    <a href="tel:<?php echo htmlspecialchars($s['phone']); ?>" class="text-cyan-400 hover:underline">
                        <?php echo htmlspecialchars($s['phone']); ?>
                    </a>
                </td>
                <?php if ($showCourse): ?>
                <td>
                    <span class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full text-xs font-semibold">
                        <?php echo htmlspecialchars($s['interest']); ?>
                    </span>
                </td>
                <?php endif; ?>
                <td class="text-gray-400 text-xs">
                    <?php echo !empty($s['current_level']) ? htmlspecialchars($s['current_level']) : '-'; ?>
                </td>
                <td class="text-gray-500 text-xs">
                    <?php echo htmlspecialchars($s['created_at']); ?>
                </td>
                <td>
                    <a href="admin.php?delete_id=<?php echo $s['id']; ?>" class="btn-delete"
                       onclick="return confirm('هل أنت متأكد تماماً من حذف هذا الطلب؟');">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        حذف
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}
?>

<script>
    document.getElementById('lastUpdate').textContent = new Date().toLocaleString('ar-EG');

    function switchTab(id, btn) {
        // Hide all panels
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        // Deactivate all buttons
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        // Show selected
        document.getElementById('tab-' + id).classList.add('active');
        btn.classList.add('active');
    }
</script>

</body>
</html>
<?php
require_once 'db_config.php';

// نظام حماية مبدئي (HTTP Authentication)
$admin_user = 'admin';
$admin_pass = 'NexTech@2026'; // يمكنك تغيير كلمة المرور هنا

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $admin_user || $_SERVER['PHP_AUTH_PW'] !== $admin_pass) {
    header('WWW-Authenticate: Basic realm="NexTech Admin Dashboard"');
    header('HTTP/1.0 401 Unauthorized');
    die('الدخول غير مصرح به.');
}

// معالجة طلب الحذف بأمان
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM students WHERE id = :id";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->execute([':id' => $delete_id]);
    header("Location: admin.php"); // إعادة تنشيط الصفحة لتحديث الجدول
    exit;
}

// جلب البيانات من القاعدة لترتيب الأحدث أولاً
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم | NexTech Academy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; background: #fff; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; }
        h2 { color: #008080; border-bottom: 2px solid #008080; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: right; }
        th { background-color: #008080; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .btn-delete:hover { background-color: #c0392b; }
        .no-data { text-align: center; padding: 20px; color: #777; }
    </style>
</head>
<body>

<div class="container">
    <h2>لوحة تحكم الطلبات والمسجلين لـ NexTech Academy</h2>
    <p>إجمالي المسجلين حالياً: <strong><?php echo count($students); ?></strong> طالب/عميل.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>الاسم الكامل</th>
                <th>الإيميل</th>
                <th>الهاتف</th>
                <th>الدولة</th>
                <th>الاهتمام الرئيسي</th>
                <th>المستوى</th>
                <th>طريقة التعلم</th>
                <th>ملاحظات</th>
                <th>التاريخ</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="11" class="no-data">لا توجد طلبات تسجيل مسجلة حتى الآن.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['id']); ?></td>
                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['phone']); ?></td>
                        <td><?php echo htmlspecialchars($student['country']); ?></td>
                        <td><span style="color: #d4af37; font-weight: bold;"><?php echo htmlspecialchars($student['interest']); ?></span></td>
                        <td><?php echo htmlspecialchars($student['current_level']); ?></td>
                        <td><?php echo htmlspecialchars($student['learning_method']); ?></td>
                        <td><?php echo htmlspecialchars($student['notes']); ?></td>
                        <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                        <td>
                            <a href="admin.php?delete_id=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد تماماً من حذف هذا الطلب؟');">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
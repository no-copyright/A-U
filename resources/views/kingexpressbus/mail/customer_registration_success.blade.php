<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đăng ký</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Cảm ơn bạn đã đăng ký tư vấn!</h2>
    <p>
        Kính gửi Quý phụ huynh <strong>{{ $customerData['full_name_parent'] }}</strong>,
    </p>
    <p>
        Chúng tôi đã nhận được thông tin đăng ký tư vấn của bạn cho học viên <strong>{{ $customerData['full_name_children'] }}</strong>.
    </p>
    <p>
        A&U English Center sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận và tư vấn chi tiết hơn về khóa học.
    </p>
    <p>
        Mọi thắc mắc vui lòng liên hệ hotline: <strong>[Số điện thoại của trung tâm]</strong> hoặc email: <strong>[Email của trung tâm]</strong>.
    </p>
    <p>Trân trọng,<br>A&U English Center.</p>
</body>
</html>
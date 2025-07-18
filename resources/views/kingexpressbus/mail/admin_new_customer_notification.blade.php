<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo khách hàng mới</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Thông báo: Có khách hàng mới đăng ký tư vấn</h2>
    <p>Một khách hàng mới vừa gửi thông tin đăng ký tư vấn qua website. Vui lòng kiểm tra và liên hệ.</p>
    <h3>Chi tiết thông tin:</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Tên phụ huynh</th>
            <td style="padding: 8px;">{{ $customerData['full_name_parent'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Số điện thoại</th>
            <td style="padding: 8px;">{{ $customerData['phone'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Email</th>
            <td style="padding: 8px;">{{ $customerData['email'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Tên học viên</th>
            <td style="padding: 8px;">{{ $customerData['full_name_children'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Ngày sinh</th>
            <td style="padding: 8px;">{{ \Carbon\Carbon::parse($customerData['date_of_birth'])->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Địa chỉ</th>
            <td style="padding: 8px;">{{ $customerData['address'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Khóa học quan tâm</th>
            <td style="padding: 8px;">{{ $customerData['training_title'] ?? 'Chưa chọn' }}</td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2; text-align: left; padding: 8px;">Ghi chú</th>
            <td style="padding: 8px;">{{ $customerData['note'] ?? 'Không có' }}</td>
        </tr>
    </table>
</body>
</html>
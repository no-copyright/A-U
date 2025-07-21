<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông báo khách hàng mới</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: rgba(255, 255, 255, 0.95);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #475569;
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            --table-hover: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            --table-header: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #0f172a;
                --bg-secondary: rgba(30, 41, 59, 0.95);
                --text-primary: #f1f5f9;
                --text-secondary: #cbd5e1;
                --text-muted: #94a3b8;
                --border-color: #334155;
                --border-light: #475569;
                --table-hover: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                --table-header: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            background: var(--bg-primary);
            min-height: 100vh;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--bg-secondary);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .header {
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .header h2 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .company-name {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 500;
            position: relative;
            z-index: 1;
            margin-top: 10px;
        }

        .notification-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.95);
            color: #ea580c;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .content {
            padding: 40px 30px;
        }

        .intro-text {
            font-size: 16px;
            color: var(--text-secondary);
            margin-bottom: 30px;
            text-align: center;
            line-height: 1.7;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .info-table th {
            background: var(--table-header);
            color: var(--text-primary);
            text-align: left;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid var(--border-color);
            width: 50%;
            position: relative;
        }

        .info-table th::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            border-radius: 2px;
        }

        .info-table td {
            padding: 16px 20px;
            color: var(--text-muted);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-light);
            font-size: 15px;
            line-height: 1.6;
        }

        .info-table tr:hover {
            background: var(--table-hover);
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: none;
        }

        .footer {
            background: var(--bg-primary);
            padding: 30px;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }

        .footer-text {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(251, 146, 60, 0.3);
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 146, 60, 0.4);
        }

        .highlight-value {
            font-weight: 600;
            color: var(--text-primary);
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .email-container {
                border-radius: 15px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h2 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .info-table th {
                width: 55%;
            }

            .info-table th,
            .info-table td {
                padding: 12px 15px;
                font-size: 14px;
            }

            .section-title {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="notification-badge">Thông báo mới</div>
            <h2>Có khách hàng mới đăng ký tư vấn</h2>
            <div class="company-name">A&U Center English</div>
        </div>

        <div class="content">
            <p class="intro-text">
                Một khách hàng mới vừa gửi thông tin đăng ký tư vấn qua website.
                Vui lòng kiểm tra thông tin chi tiết bên dưới và liên hệ sớm nhất có thể.
            </p>

            <h3 class="section-title">Chi tiết thông tin:</h3>

            <table class="info-table">
                <tr>
                    <th>Tên phụ huynh</th>
                    <td class="highlight-value">{{ $customerData['full_name_parent'] }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td class="highlight-value">{{ $customerData['phone'] }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td class="highlight-value">{{ $customerData['email'] }}</td>
                </tr>
                <tr>
                    <th>Tên học viên</th>
                    <td class="highlight-value">{{ $customerData['full_name_children'] }}</td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td>{{ \Carbon\Carbon::parse($customerData['date_of_birth'])->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>{{ $customerData['address'] }}</td>
                </tr>
                <tr>
                    <th>Khóa học quan tâm</th>
                    <td>{{ $customerData['training_title'] ?? 'Chưa chọn' }}</td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td>{{ $customerData['note'] ?? 'Không có' }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p class="footer-text">
                Hãy liên hệ với khách hàng trong thời gian sớm nhất để có thể hỗ trợ tốt nhất.
            </p>
            <a href="{{ route('admin.customers.index') }}" class="action-button">Xem chi tiết trong hệ thống</a>
        </div>
    </div>
</body>
</html>
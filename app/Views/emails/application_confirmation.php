<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 20px;
        }
        .card {
            background: #ffffff;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        h2 {
            color: #5a4fcf;
        }
        .footer {
            font-size: 13px;
            color: #777;
            margin-top: 25px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>🎉 Application Submitted Successfully!</h2>

    <p>Hello <strong><?= esc($name) ?></strong>,</p>

    <p>
        Thank you for applying through our Job Application Portal.
        We have successfully received your application.
    </p>

    <p>
        📌 <strong>Application Status:</strong> Under Review  
        <br>
        🕒 Our team will contact you if you are shortlisted.
    </p>

    <p>
        Please keep checking your email for further updates.
    </p>

    <p>Best regards,<br>
    <strong>Recruitment Team</strong></p>

    <div class="footer">
        © <?= date('Y') ?> Job Application Portal
    </div>
</div>

</body>
</html>

<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html lang="de" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressum - Beethoven City Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.8; }
        .legal-container { max-width: 800px; margin: 50px auto; background: #fff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h1, h2 { color: #1e3a8a; font-weight: 700; }
        h1 { margin-bottom: 30px; font-size: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        h2 { font-size: 1.25rem; margin-top: 25px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="legal-container">
    <a href="index.php?url=home" class="btn btn-outline-primary btn-sm mb-4">&larr; Zurück zur Startseite</a>
    
    <h1>Impressum</h1>

    <h2>Angaben gemäß § 5 TMG</h2>
    <p>
        Beethoven City Services GmbH (Projekt)<br>
        Münsterstraße 123<br>
        53111 Bonn, Deutschland
    </p>

    <h2>Vertreten durch</h2>
    <p>Geschäftsführung: Nour El-huda Nour</p>

    <h2>Kontakt</h2>
    <p>
        Telefon: +49 (0) 228 1234567<br>
        E-Mail: admin@beethoven-services.de
    </p>

    <h2>Hhaftung für Inhalte</h2>
    <p>Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.</p>

    <h2>Urheberrecht</h2>
    <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.</p>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2>Bilieto statusas pakeistas</h2>

    <p>Sveiki,</p>

    <p>Jūsų bilietas buvo pradėtas vykdyti.</p>

    <p>
        <strong>ID:</strong> {{ $bilietas->bilieto_id }}<br>
        <strong>Pavadinimas:</strong> {{ $bilietas->pavadinimas }}<br>
        <strong>Prioritetas:</strong> {{ $bilietas->prioritetas }}<br>
        <strong>Kategorija:</strong> {{ $bilietas->kategorija }}<br>
    </p>

    <p>Statusas pakeistas iš <strong>Laukiama</strong> į <strong>Vykdoma</strong>.</p>

    <p>Gražios dienos!</p>
</body>
</html>

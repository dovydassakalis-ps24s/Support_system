<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2>Bilietas uždarytas</h2>

    <p>Sveiki,</p>

    <p>Jūsų bilietas buvo uždarytas.</p>

    <p>
        <strong>ID:</strong> {{ $bilietas->bilieto_id }}<br>
        <strong>Pavadinimas:</strong> {{ $bilietas->pavadinimas }}<br>
        <strong>Prioritetas:</strong> {{ $bilietas->prioritetas }}<br>
        <strong>Kategorija:</strong> {{ $bilietas->kategorija }}<br>
    </p>

    <p>
        <strong>Komentaras:</strong><br>
        {{ $bilietas->komentaras }}
    </p>

    <p>Statusas pakeistas iš <strong>Vykdoma</strong> į <strong>Įvykdyta</strong>.</p>

    <p>Gražios dienos!</p>
</body>
</html>
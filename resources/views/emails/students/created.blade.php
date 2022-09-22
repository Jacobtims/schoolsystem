<!DOCTYPE html>
<html lang="nl">
<body>

<h6>Nieuwe account aangemaakt!</h6>

Er is een studenten account voor jouw aangemaakt.
<table class="table table-borderless">
    <tbody>
    <tr>
        <th>Je gebruikersnaam:</th>
        <td>{{ $email }}</td>
    </tr>
    <tr>
        <th>Je wachtwoord:</th>
        <td>{{ $password }}</td>
    </tr>
    </tbody>
</table>

Aub zo snel mogelijk inloggen en <b>wachtwoord veranderen</b>!


Met vriendelijke groet,<br>
{{ config('app.name') }}

</body>
</html>

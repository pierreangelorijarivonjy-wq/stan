<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Liste des Utilisateurs EduPass-MG</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            bg-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>EduPass-MG</h1>
        <h2>Liste des Utilisateurs</h2>
        <p>Généré le : {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom & Prénoms</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Mot de passe (Hash)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    <td>{{ $user->status }}</td>
                    <td style="font-size: 8px; word-break: break-all;">{{ $user->password }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        EduPass-MG - Plateforme de Télé-enseignement - Madagascar
    </div>
</body>

</html>
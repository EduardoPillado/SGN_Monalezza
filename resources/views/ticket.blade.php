<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Monalezza Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .receipt {
            background-color: white;
            padding: 20px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
            height: 100px;
            border-radius: 150px;

        }
        h1, h2 {
            text-align: center;
            margin: 10px 0;
        }
        .date {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px dotted #ddd;
        }
        .total {
            font-weight: bold;
        }
        .thanks {
            text-align: center;
            margin-top: 20px;
        }
        .pizza-border {
            border-bottom: 10px solid transparent;
            border-image: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M50 0 L100 100 L0 100 Z" fill="%23000"/></svg>') 0 0 10 0 repeat;
            padding-bottom: 10px;
           
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="logo">
            <img src="img/logo_lamonalezza.webp" alt="La Monalezza Logo">
        </div>
        <h1>LA MONALEZZA</h1>
        <p class="date">17/09/2024 - 08:30PM</p>
        <table>
            <tr>
                <th>ARTÍCULO</th>
                <th>PRECIO</th>
            </tr>
            <tr>
                <td>x1 pizza 1</td>
                <td>$ 150</td>
            </tr>
        </table>
        <table>
            <tr class="total">
                <td>TOTAL</td>
                <td>$ 150</td>
            </tr>
            <tr>
                <td>PAGÓ</td>
                <td>$ 200</td>
            </tr>
            <tr>
                <td>SU CAMBIO</td>
                <td>$ 50</td>
            </tr>
        </table>
        <p class="thanks">¡GRACIAS POR TU COMPRA!</p>
        <div class="pizza-border"></div>
    </div>
</body>
</html>
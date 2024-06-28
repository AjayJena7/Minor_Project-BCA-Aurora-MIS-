<?php
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;


function calculatePriceWithGST($price) {
    return $price * 1.05; // GST at 5%
}


$items = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    for ($i = 1; $i <= $_POST['num_books']; $i++) {
        $bookId = $_POST['book_id_' . $i];
        $bookName = $_POST['book_name_' . $i];
        $price = $_POST['price_' . $i];


        $item = [
            'id' => $bookId,
            'name' => $bookName,
            'price' => $price,
        ];

       
        $item['price_with_gst'] = calculatePriceWithGST($item['price']);

        // Add the new item to the items array
        $items[] = $item;
    }

    // Generate PDF only if there are items
    if (!empty($items)) {
        // Create a new Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        // Load HTML content
        $html = '<html>
                   <head>
                       <style>
                           body {
                               font-family: \'Arial\', sans-serif;
                               background-color: #f5f5f5;
                               margin: 0;
                               padding: 0;
                           }

                           .container {
                               max-width: 800px;
                               margin: 20px auto;
                               background-color: #fff;
                               padding: 20px;
                               border-radius: 8px;
                               box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                           }

                           h1 {
                               color: #333;
                               text-align: center;
                           }

                           form {
                               display: flex;
                               flex-direction: column;
                               align-items: center;
                           }

                           label {
                               margin: 10px 0 5px;
                               font-weight: bold;
                           }

                           input {
                               padding: 8px;
                               margin-bottom: 15px;
                               width: 100%;
                               box-sizing: border-box;
                           }

                           button {
                               padding: 10px;
                               background-color: #4caf50;
                               color: #fff;
                               border: none;
                               border-radius: 4px;
                               cursor: pointer;
                               font-size: 16px;
                               transition: background-color 0.3s;
                           }

                           button:hover {
                               background-color: #45a049;
                           }

                           table {
                               width: 100%;
                               border-collapse: collapse;
                               margin-top: 20px;
                           }

                           th, td {
                               border: 1px solid #ddd;
                               padding: 12px;
                               text-align: left;
                           }

                           th {
                               background-color: #4caf50;
                               color: #fff;
                           }
                       </style>
                   </head>
                   <body>
                       <div class="container">
                           <h1>Bookstore Bill</h1>
                           <table>
                               <tr>
                                   <th>Item ID</th>
                                   <th>Item Name</th>
                                   <th>Price</th>
                                   <th>Price (with GST)</th>
                               </tr>';

        foreach ($items as $item) {
            $html .= '<tr>
                         <td>' . $item['id'] . '</td>
                         <td>' . $item['name'] . '</td>
                         <td>$' . number_format($item['price'], 2) . '</td>
                         <td>$' . number_format($item['price_with_gst'], 2) . '</td>
                       </tr>';
        }

        $html .= '</table>
                       </div>
                   </body>
               </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
        exit; 
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Bill</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            box-sizing: border-box;
        }

        h1 {
            color: #343a40;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            color: #495057;
        }

        input {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
    <script>
        function addMore() {
            var numBooks = parseInt(document.getElementById("num_books").value);
            numBooks++;
            var container = document.getElementById("product-container");
            var newProduct = document.createElement("div");
            newProduct.innerHTML = "<div><label for='book_id_" + numBooks + "'>Book " + numBooks + " ID:</label>" +
                "<input type='text' id='book_id_" + numBooks + "' name='book_id_" + numBooks + "' required></div>" +
                "<div><label for='book_name_" + numBooks + "'>Book " + numBooks + " Name:</label>" +
                "<input type='text' id='book_name_" + numBooks + "' name='book_name_" + numBooks + "' required></div>" +
                "<div><label for='price_" + numBooks + "'>Book " + numBooks + " Price:</label>" +
                "<input type='number' id='price_" + numBooks + "' name='price_" + numBooks + "' step='0.01' required></div>";
            container.appendChild(newProduct);
            document.getElementById("num_books").value = numBooks;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1> AURORA Bookstore Bill</h1>
        <form method="post">
            <label for="num_books">Number of Products:</label>
            <input type="number" id="num_books" name="num_books" value="1" min="1" required>

            <div id="product-container">
                <div>
                    <label for="book_id_1">product 1 ID:</label>
                    <input type="text" id="book_id_1" name="book_id_1" required>
                </div>
                <div>
                    <label for="book_name_1">product 1 Name:</label>
                    <input type="text" id="book_name_1" name="book_name_1" required>
                </div>
                <div>
                    <label for="price_1">product 1 Price:</label>
                    <input type="number" id="price_1" name="price_1" step="0.01" required>
                </div>
            </div>

            <button type="button" onclick="addMore()"><i class="fas fa-plus"></i> Add More</button>
            <button type="submit"><i class="fas fa-file-pdf"></i> Generate Bill</button>
        </form>

        <?php
        
        ?>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'Cuaderno de campo' ?></title>
    <meta name="description" content="<?= $description ?? 'Cuaderno de campo' ?>">
    <style>
      header {
        margin: 0;
        padding: 0;
        width: 100%;
      }
      header h1 {
        display: inline-block;
        margin: 0;
        padding: 0;
        width: 50%;
      }
      header h2 {
        display: inline-block;
        margin: 0;
        padding: 0;
        width: 50%;
      }

      table {
        width: 100%;
        page-break-inside: auto;
      }
      table thead {
        border-bottom: 3px solid #444;
      }

      table thead tr th {
        text-align: left;
      }
      table tbody {
        border-bottom: 1px solid #444;
      }
      table tbody tr:nth-child(1) td {
        padding-top: 5px;
      }
      table tbody tr:nth-child(2) td {
        color: #444;
        padding-bottom: 7px;
      }
      .text-end {
        text-align: right;
      }
    </style>
  </head>
  <body>
    <header>
      <h1>AgrOS</h1>
      <h2 class="text-end">Cuaderno de Campo</h2>
    </header>
    <?= View::body() ?>
  </body>
</html>
<table>
  <thead>
    <tr>
      <th>Fecha / Categ.</th>
      <th>Operador / Producto</th>
      <th>Tarea</th>
      <th class="text-end">Cantidad</th>
    </tr>
  </thead>
  <? foreach ($tasks as $task): ?>
  <tbody>
    <tr>
      <td><?= $task['date'] ?></td>
      <td><strong><?= $task['user_name'] ?></strong></td>
      <td><?= $task['name'] ?></td>
      <td></td>
    </tr>
    <tr>
      <td><strong><?= $task['category_name'] ?? 'ITEM' ?>:</strong></td>
      <td colspan="2"><span><?= $task['product_name'] ?></span></td>
      <td class="text-end"><?= $task['quantity'] ?></td>
    </tr>
  </tbody>
  <? endforeach ?>
</table>
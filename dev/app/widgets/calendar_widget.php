<?php
/* Widgets de calendario */

Widget::register('calendars', function($p1){
?>
<div class="months d-flex flex-column" data-bind="foreach: {data: months, as: 'M'}, css: css">
  <div class="month">
    <table>
      <thead>
        <tr>
          <th colspan="100%" data-bind="text: name"></th>
        </tr>
        <tr data-bind="foreach: Month.wdays">
          <th data-bind="text: label"></th>
        </tr>
      </thead>
      <tbody data-bind="foreach: weeks">
        <tr data-bind="foreach: {data: $data, as: 'd'}">
          <td data-bind="text: d.day, css: d.css">1</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php });
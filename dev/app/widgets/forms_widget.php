<?php
Widget::register('values', function($p1){
  list($field, $opt) = $p1[0];
  $class=['form-control'];
  $size = $opt['size'] ?? '';
  if($size) $class[]="form-control-$size";
  return [
    'field' => $field,
    'id' => $field,
    'name' => $field,
    'label' => $field,
    'size' => $size,
    'type' => $opt['type'] ?? 'text',
    'col' => $opt['col'] ?? '-md6',
    'class' => ['form-control'],
    'class' => implode(' ', $class),
    'disabled' => $opt['disabled'] ?? False ? 'disabled':'',
    'required' => $opt['required'] ?? False ? 'required':'',
    'options' => $opt['options'] ?? False,
    'caption' => $opt['caption'] ?? False,
    'columns' => $opt['columns'] ?? False,
    'form_layout' => $opt['form_layout'] ?? [],
  ];
});

Widget::register('input', function($p1) {
  extract(Widget::values($p1));
  $data_bind = "data-bind=\"value: $name\"";
  if($form_layout && $form_layout['horizontal']):
    $label_w = $form_layout['label'] ?? 'sm-2';
    $input_w = $form_layout['input'] ?? 'sm-10';
  ?>
    <div class="row mb-md-3 g-2">
      <label for="<?= $id ?>" class="col-<?= $label_w ?> col-form-label"><?= _t($label) ?></label>
      <div class="col-<?= $input_w ?>">
        <input <?= $data_bind ?> type="<?= $type ?>" class="form-control" id="<?= $id ?>" name="<?= $name ?>"
          <?= $disabled ?> <?= $required ?> />
      </div>
    </div>
  <? else: ?>
    <div class="col-<?= $col ?>">
      <label for="<?= $id ?>" class="form-label d-none d-md-block"><?= _t($label) ?></label>
      <input <?= $data_bind ?> type="<?= $type ?>" class="<?= $class ?>" id="<?= $id ?>"
        name="<?= $name ?>" <?= $disabled ?> <?= $required ?>/>
    </div>
  <? endif ?>
<?php });

Widget::register('text', function($p1) {
  extract(Widget::values($p1)); ?>
  <div class="col-<?= $col ?>">
    <label for="<?= $id ?>" class="form-label d-none d-md-block"><?= _t($label) ?></label>
    <textarea data-bind="value: <?= $name ?>" class="<?= $class ?>" name="<?= $name ?>"
      id="<?= $id ?>" rows="3" <?= $disabled ?> <?= $required ?>></textarea>
  </div>
<?php });

Widget::register('select', function($p1) {
  extract(Widget::values($p1));
  $class=['form-select'];
  if($size) $class[]="form-select-$size";
  $class = implode(' ', $class);
  $data_bind=[];
  if($options) {
    if(!$columns) {
      $columns = 'id,name';
    }
    list($option_id,$option_txt) = explode(',',$columns);
    $data_bind = "data-bind=\"options: $options, optionsText:'$option_txt',";
    $data_bind .= "optionsValue:'$option_id', value: $name";
    if($caption) {
      $data_bind .= ", optionsCaption: '$caption'\"";
    } else { $data_bind .= "\""; }
  } else {
    $data_bind = "data-bind=\"value: $name\"";
  }
  ?>
  <div class="col-<?= $col ?>">
    <label for="<?= $id ?>" class="form-label d-none d-md-block"><?= _t($label) ?></label>
    <select <?= $data_bind ?> class="<?= $class ?>" name="<?= $name ?>" id="<?= $id ?>"
      <?= $disabled ?> <?= $required ?>></select>
  </div>
<?php });

Widget::register('select2', function($p1) {
  extract(Widget::values($p1));
  $class=['form-select'];
  if($size) $class[]="form-select-$size";
  $class = implode(' ', $class);?>
  <div class="col-<?= $col ?>">
    <label for="" class="form-label d-none d-md-block">Taxes</label>
    <select class="js-states form-control form-control-sm" name="<?= $name ?>" id="<?= $id ?>"
      <?= $disabled ?> <?= $required ?>
      data-bind="select2: {},
      select2Options: taxesList,
      selectedOptions: <?= $name ?>"
      multiple="multiple"
      style="width: 100%;">
    </select>
  </div>
<?php });

Widget::register('datalist', function($p1) {
  extract(Widget::values($p1));
  $dlId = "dataList$id"; ?>
  <div class="col-<?= $col ?>">
    <label for="<?= $id ?>" class="form-label d-none d-md-block"><?= _t($label) ?></label>
    <input data-bind="value: <?= $name ?>" class="<?= $class ?>" id="<?= $id ?>"
      name="<?= $name ?>" list="<?= $dlId ?>" <?= $disabled ?> <?= $required ?>/>
    <datalist id="<?= $dlId ?>">
      <option value="San Francisco">
      <option value="New York">
      <option value="Seattle">
      <option value="Los Angeles">
      <option value="Chicago">
    </datalist>
  </div>
<?php });

Widget::register('fields', function($p1) {
  $form = $p1[0];
  $options = $p1[1] ?? [];
  foreach($form as $field => $opt){
    $type = $opt['type'];
    $opt['form_layout'] = $options;
    switch($type) {
      case 'textarea': W::text($field, $opt); break;
      case 'select': W::select($field, $opt); break;
      case 'datalist': W::datalist($field, $opt); break;
      case 'select2': W::select2($field, $opt); break;
      default: W::input($field, $opt);
    }
  }
});
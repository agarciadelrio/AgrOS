<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::category_menu() ?>
    </div>
    <div class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <h1><i class="<?= W::fa('categories') ?>"></i> <?= _t('categories') ?></h1>
        <hr/>
        <div id="categoryTree">
          <table class="table">
            <tbody>
              <tr>
                <td>
                  <div id="categoryTree">
                    <!-- TREE -->
                    <div data-bind="template: { name: 'listTemplate' }" class="list-tree"></div>
                  </div>
                </td>
                <td class="w-50">
                  <?php /*
                  */ ?>
                  <?php /* <div data-bind="if: selectedCategory()"> */ ?>
                  <div class="sticky-top pt-3">
                    <div class="form-wrapper bg-dark text-light rounded p-3 shadow">
                      <div data-bind="visible: !selectedCategory()">
                        <h2><i class="fa fa-edit"></i> Seleccione una Categoría</h2>
                      </div>
                      <div data-bind="with: selectedCategory">
                        <h2><i class="fa fa-edit"></i> <span data-bind="text: name">Categoría</span></h2>
                        <!-- FORMULARIO -->
                        <form data-bind="submit: submitData" id="formCategory" action="#" method="post">
                          <input data-bind="value: id" type="hidden" id="categoryId" readonly disabled>
                          <div class="mb-3">
                            <label for="categoryName" class="form-label"><?= _t('name') ?></label>
                            <input data-bind="value: name" type="text" class="form-control" id="categoryName">
                          </div>
                          <div data-bind="visible: parent" class="mb-3">
                            <label for="categoryParent" class="form-label"><?= _t('parent_category') ?></label>
                            <select data-bind="
                              options: tree.options_list,
                              optionsText: 'text',
                              optionsValue: 'id',
                              value: category_id"
                              class="form-select" id="categoryParent">
                            </select>
                          </div>
                          <div class="mb-3 text-end">
                            <span data-bind="visible: parent">
                              <button data-bind="click: deleteMe" class="btn btn-danger"><?= _t('delete') ?></button>
                            </span>
                            <button class="btn btn-primary"><?= _t('update') ?></button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<template id="listTemplate">
  <ul data-bind="foreach: ownCategory">
    <li data-bind="template: { name: 'itemTemplate' }"></li>
  </ul>
</template>

<template id="itemTemplate">
  <div data-bind="click: select">
    <span data-bind="text: name"></span>
    <a class="link-success" href="#">
      <i class="fa fa-plus-square"></i>
    </a>
  </div>
  <div data-bind="template: { name: 'listTemplate' }"></div>
</template>

<script defer>
class Item {
  constructor(tree, parent, item) {
    this.tree = tree
    this.tree.items[item.id] = this
    this.parent = ko.observable(parent)
    this.id = item.id
    this.name = ko.observable(item.name)
    this.category_id = ko.observable(item.category_id)
    this.ownCategory = ko.observableArray(
      item.ownCategory.map(i => new Item(tree, this, i))
    )
  }
  select() {
    this.tree.selectedCategory(this)
  }
  deleteMe(item) {
    console.log('DELETE', this)
    console.log('ITEM',item)
    if(confirm(`¿Quiere continuar para eliminar esta categoría: ${this.name()}?`)) {

    }
    return false
  }
  submitData(form) {
    fetch('/api/v1/category/update',{
      method: 'POST',
      body: JSON.stringify({
        id: this.id,
        name: this.name(),
        parent: this.category_id(),
      })
    })
    .then(response => response.json())
    .then(data => {
      if(this.parent().id!=this.category_id()) {
        // hay que cambiar de padre esta categoría
        var new_parent = tree.searchById(this.category_id())
        var removed = this.parent().ownCategory.remove(this)
        this.parent(new_parent)
        this.parent().ownCategory.push(this)
        this.tree.options_list(data.options_list)
        for(var _i in this.tree.options_list()) {
          var i = this.tree.options_list()[_i]
          if(i.id==new_parent.id) {
            this.category_id(new_parent.id)
            break
          }
        }
        //this.category_id()
      }
    })
    .catch(error => console.error('ERROR:', error))
    return false
  }
}
class Tree {
  constructor(rootItem, options_list) {
    this.items = {}
    this.options_list = ko.observableArray(options_list)
    var root = new Item(this, null, rootItem)
    this.ownCategory = ko.observableArray([root])
    this.selectedCategory = ko.observable(null)
  }
  searchById(id) {
    return this.items[id]||null
  }
}

var tree_data = <?= $tree ?? '{}' ?>;
var options_list = <?= $options_list ?? '[]' ?>;
var tree = new Tree(tree_data, options_list);
var element = document.getElementById('categoryTree')
ko.applyBindings(tree, element)
</script>

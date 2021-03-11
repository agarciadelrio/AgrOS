<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::category_menu() ?>
    </div>
    <div class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <header class="row justify-content-between align-items-center p-0 mb-3">
          <h1 class="col-12 col-sm-auto text-begin"><i class="<?= W::fa('categories') ?>"></i> <?= _t('categories') ?></h1>
        </header>
        <hr/>
        <div id="categoryTree" class="row">
          <div class="col-5">
            <table class="table">
              <tbody>
                <tr>
                  <td>
                    <div id="categoryTree">
                      <!-- TREE -->
                      <div data-bind="template: { name: 'listTemplate' }" class="list-tree"></div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-7">
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
                      <span data-bind="visible: parent() && ownCategory().length===0">
                        <button data-bind="click: deleteMe" class="btn btn-danger"><?= _t('delete') ?></button>
                      </span>
                      <button class="btn btn-primary"><?= _t('update') ?></button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
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
      <i data-bind="click: addCategory" class="fa fa-plus-square"></i>
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

  addCategory() {
    console.log('ADD CATEGORY')
    var new_category = prompt('Nueva Categoría','')
    if(new_category) {
      fetch('/api/v1/category',{
        method: 'POST',
        body: JSON.stringify({
          name: new_category,
          category_id: this.id,
        })
      })
      .then(response => response.json())
      .then(data => {
        console.log('DATA CATEGORY', data)
        // Crear categoría
        var cat = new Item(this.tree, this, {
          id: data.id,
          name: data.item.name,
          category_id: data.item.category_id,
          ownCategory: [],
        })
        location.reload();
      })
      .catch(err => {
        console.error('ERROR CREANDO CATEGORIA: ', err)
      })
    }
  }

  select() {
    this.tree.selectedCategory(this)
  }
  deleteMe(item) {
    console.log('DELETE', this)
    console.log('ITEM',item)
    if(confirm(`¿Quiere continuar para eliminar esta categoría: ${this.name()}?`)) {
      fetch('/api/v1/category/delete',{
        method: 'POST',
        body: JSON.stringify({
          id: this.id,
        })
      })
      .then(response => response.json())
      .then(data => {
        console.log('DATA', data)
        location.reload();
      })
      .catch(error => console.error('ERROR AL ELIMINAR', error))
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

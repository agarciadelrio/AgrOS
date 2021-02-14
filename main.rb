require 'erb'
require 'json'

class ShoppingList
  include ERB::Util
  attr_accessor :out_dir, :model_name, :model_plural_name, :item_name, :item_array_name

  def initialize(out_dir, model_name, model_plural_name, item_name, item_array_name)
    @out_dir = out_dir
    @model_name = model_name
    @model_plural_name = model_plural_name
    @item_array_name = item_array_name
    @item_name = item_name
    model_schema_name = File.join('./dev/api/schema',"#{@item_name}.json")
    if File.exist?(model_schema_name)
      @model =  OpenStruct.new JSON.parse(File.read(model_schema_name))
    else
      @model = nil
    end
    @templates = [
      './dev/erb/model.ts.erb',
      './dev/erb/model.pug.erb',
      './dev/erb/model_form.pug.erb',
      './dev/erb/controller.php.erb',
      './dev/erb/extra.php.erb',
    ]
  end

  def render(ind)
    template = File.read(@templates[ind])
    ERB.new(template,nil,'-').result(binding)
  end

  def save()
    puts "Created."
    exts = ['.ts', '.pug', '_form.pug', 'Controller.php', '_extra.php']
    file_names = [
      File.join('./dev/app',@item_name, "#{@item_name}.ts"),
      File.join('./dev/app',@item_name, "#{@item_name}.pug"),
      File.join('./dev/app',@item_name, "#{@item_name}_form.pug"),
      File.join('./dev/api/app/controllers', "#{@model_plural_name}Controller.php"),
      File.join(@out_dir, "#{@item_name}#{exts[4]}"),
    ]
    for t in (0..@templates.count-1)
      file = file_names[t]
      puts t
      puts file
      File.open(file, "w+") do |f|
        f.write(render(t))
      end
    end
  end

end

for d in [
  #['./tmp', 'Authorization', 'Authorizations','authorization', 'authorizations'],
  #['./tmp', 'Company', 'Companies','company', 'companies'],
  #['./tmp', 'Contact', 'Contacts','contact', 'contacts'],
  #['./tmp', 'Farm', 'Farms','farm', 'farms'],
  #['./tmp', 'Handler', 'Handlers','handler', 'handlers'],
  #['./tmp', 'Item', 'Items','item', 'items'],
  #['./tmp', 'Member', 'Members','member', 'members'],
  #['./tmp', 'Parcel', 'Parcels','parcel', 'parcels'],
  #['./tmp', 'Permission', 'Permissions','permission', 'permissions'],
  #['./tmp', 'Plot', 'Plots','plot', 'plots'],
  #['./tmp', 'Product', 'Products','product', 'products'],
  #['./tmp', 'Profile', 'Profiles','profile', 'profiles'],
  #['./tmp', 'Property', 'Properties','property', 'properties'],
  #['./tmp', 'Stuff', 'Stuffs', 'stuff', 'stuffs'],
  #['./tmp', 'Task', 'Tasks', 'task', 'tasks'],
  #['./tmp', 'Taxonomy', 'Taxonomies', 'taxonomy', 'taxonomies'],
  ['./tmp', 'Team', 'Teams', 'team', 'teams'],
  #['./tmp', 'User', 'Users', 'user', 'users'],
  #['./tmp', 'Variety', 'Varieties', 'variety', 'varieties'],
]
ShoppingList.new(d[0],d[1],d[2],d[3],d[4]).save
end

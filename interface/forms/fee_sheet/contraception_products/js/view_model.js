/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function populate_contraception_products(data)
{
    var category=new code_category("Products:"+data.method);
    var products = data['products'];
    for(var idx=0;idx<products.length;idx++)
    {
        var cur_product=products[idx];
        var product_code="PROD|"+cur_product.drug_id+"|"+cur_product.selector;
        var title=cur_product.drug_id + ":" + cur_product.selector;
        if(cur_product.name !== cur_product.selector)
        {
            title +=" "+cur_product.name;
        }
        var choice=new code_choice(title,product_code);
        category.codes.push(choice);
    }
    if(products!==null)
    {
        codes_choices_vm.categories.push(category);
    }


}

function lookup_contraception_products()
{
    var conmeth=$("input[name='ippfconmeth']");
    var conmethcode=conmeth.val();
    var methods_elements=$("tr > td.billcell > input[type='hidden'][name$='[method]']");
    var methods=[];
    methods_elements.each(function(idx,elem)
    {
        methods.push(elem.value);
    });
    conmethcode=methods_elements.get(0).value;
    if(methods.length!=0)
    {
        $.ajax(webroot+"/interface/forms/fee_sheet/contraception_products/ajax/find_contraception_products.php",
        {
            type: "POST",
            dataType: "json",
            data: {
                methods:methods
            },
            success: function(data)
            {
                for(var idx=0;idx<data.length;idx++)
                {
                    populate_contraception_products(data[idx]);
                }
            }
        });
    }

}

lookup_contraception_products();

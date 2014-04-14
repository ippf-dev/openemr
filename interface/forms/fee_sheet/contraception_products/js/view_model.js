/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function populate_contraception_products(data,status,jqXHR)
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
    if(conmethcode!=null)
    {
        $.ajax(webroot+"/interface/forms/fee_sheet/contraception_products/ajax/find_contraception_products.php",
        {
            type: "POST",
            dataType: "json",
            data: {
                ippfconmeth: conmethcode
            },
            success: populate_contraception_products
        });
    }

}

lookup_contraception_products();

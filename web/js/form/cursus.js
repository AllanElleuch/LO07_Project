var $collectionHolder;

// setup an "add a tag" link
var $addTagLink = $('<hr><a href="#" class="add_tag_link">Ajouter un élément</a>');
//var $newLinkLi = $('<li></li>').append($addTagLink);
var $newLinkLi = $('<li></li>');

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    //$collectionHolder = $('div#prototypeContainer')
    $collectionHolder = $('ul.elemFormation');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    /*$addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });*/

    $('#bouttonAjouterCours').on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        ajouterCours($collectionHolder, $newLinkLi);
    });
    addTagForm2($collectionHolder, $newLinkLi);
});
/*
function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

*/
function addTagForm2($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    //var $newFormLi = $('<li></li>').append(newForm);
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}



function ajouterCours($collectionHolder, $newLinkLi){
    //var $form=document.createElement("div");
    var list=$(".elemFormation li div .form-control");
    //$(".elemFormation li div ").appendTo($form)
    //var $form=$(".elemFormation li div ").clone();
    //var $id=$(".elemFormation li div").get(0).id
    var $id=$collectionHolder.find("li div").get(0).id
    var $form=$collectionHolder.find("#"+$id).clone()
    //$(".elemFormation li #form_elementsFormations_0").clone().appendTo("#main_content .tab-pane.active .accordion")
    $(".elemFormation li "+$id).clone().appendTo("#main_content .tab-pane.active .accordion")
    $(".elemFormation li div ").detach();
      var $html=$form;
    //$(".elemFormation li div ").remove();
    //var $id=$form[0].attr('id')

    //$(".elemFormation li div ").detach().appendTo($("#main_content .tab-pane.active"))

    var description="";

    /*for (var i = 0; i < list.length; i++) {
        description+=list[i].value+"-";
    }
    */
    description+=list[0].value +" : "+list.find("option:selected").get(1).text
    var card= `
      <div class="card">
        <div class="card-header" role="tab" id=`+"headingOne"+$id+`>
          <h5 class="mb-0">
            <a data-toggle="collapse" data-parent="#accordion" href=`+"#collapseOne"+$id+` aria-expanded="true" aria-controls="collapseOne">
              `+description+`
            </a>
          </h5>
        </div>
        <div id=`+"collapseOne"+$id+` class="collapse" role="tabpanel" aria-labelledby="`+"headingOne"+$id+`>
          <div class="card-block">`+
          `
          </div>
        </div>`
        $("#main_content .tab-pane.active .accordion").append(card);
        var $id2="#collapseOne"+$id;
        //$($id2).append($form);
        //$(".elemFormation li").detach().appendTo($($id2))
        $($html).appendTo($($id2))

        addTagForm2($collectionHolder, $newLinkLi);

//$(".elemFormation li div ").appendTo($id2)
}

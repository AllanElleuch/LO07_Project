var $collectionHolder;

// setup an "add a tag" link
var $addTagLink = $('<hr><a href="#" class="add_tag_link">Ajouter un élément</a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.elemFormation');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });

    $('#bouttonAjouterCours').on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        ajouterCours($collectionHolder, $newLinkLi);
    });
    addTagForm($collectionHolder, $newLinkLi);
});

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
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}


function ajouterCours($collectionHolder, $newLinkLi){
    var $form=document.createElement("div");
    $(".elemFormation li div ").detach().appendTo($form)
    //var $id=$form[0].attr('id')
    var $id=$($form).children()[0].id

    //$(".elemFormation li div ").detach().appendTo($("#main_content .tab-pane.active"))
    addTagForm($collectionHolder, $newLinkLi);

    var card= `
      <div class="card">
        <div class="card-header" role="tab" id=`+"headingOne"+$id+`>
          <h5 class="mb-0">
            <a data-toggle="collapse" data-parent="#accordion" href=`+"#collapseOne"+$id+` aria-expanded="true" aria-controls="collapseOne">
              Collapsible Group Item #1
            </a>
          </h5>
        </div>
        <div id=`+"collapseOne"+$id+` class="collapse show" role="tabpanel" aria-labelledby="`+"headingOne"+$id+`>
          <div class="card-block">`+$($form).html()+
          `
          </div>
        </div>`
        $("#main_content .tab-pane.active .accordion").append(card);

}

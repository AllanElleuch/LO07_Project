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

  $('body').on('click', '#newCoursButton', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();
    console.log(
      $(e.target).parent().parent().parent()
    );
    console.log(
      $(e.target)[0].getAttribute('class')
    );
    id=$(e.target).get(0).id;
    $collectionHolder=$("#cursusTableContainer");
    if(id=="newCoursButton"){
      $target=$(e.target).parent().parent().parent();

    }
    else{
      $target=$(e.target).parent().parent().parent().parent();

    }


    ajouterCours($collectionHolder, $target);
  });

  $('.nav-tabs ').on('input', 'input', function() {

    var input= $(this).val()
    var name= $(this).attr('name');

    var list = $(".tab-pane.active .form")
    for (var i = 0; i < list.length; i++) {
      var id=list[i].id
      $("#"+id+name).val(input)
    }
  });
});

function addTagForm2($collectionHolder, $target) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype');

  // get the new index
  if($collectionHolder.data('index') == undefined ){
    $collectionHolder.data('index',0)
  }

  var index = $collectionHolder.data('index');
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have
  var newForm = prototype.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);
  $target.append(newForm);
}



function ajouterCours($collectionHolder, $target){



  addTagForm2($collectionHolder, $target);
  $('#semestresNav input').trigger('input');

}

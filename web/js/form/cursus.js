

jQuery(document).ready(function() {
  InitialisationDOM();

});

// Va permettre d'ajouté dynamiquement des cours aux tableaux de semestre
function InitialisationDOM(){

  // Get the ul that holds the collection of tags
  //$collectionHolder = $('div#prototypeContainer')

  var $collectionHolder;
  //var $newLinkLi = $('<li></li>').append($addTagLink);
  var $newLinkLi = $('<li></li>');
  $collectionHolder = $('ul.elemFormation');

  // add the "add a tag" anchor and li to the tags ul
  $collectionHolder.append($newLinkLi);

  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  $collectionHolder.data('index', $collectionHolder.find(':input').length);

  $('body').on('click', '#newCoursButton', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();
    window.l=$(e.target)[0]
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
}




function ajouterCours($collectionHolder, $target){

  var prototype = $collectionHolder.data('prototype');

  // get the new index
  if($collectionHolder.data('index') == undefined ){
    $collectionHolder.data('index',$($target).children().length-1)
  }

  var index = $collectionHolder.data('index');
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have
  var newForm = prototype.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);
  $target.append(newForm);

  $('#semestresNav input').trigger('input');

}



$( document ).ready(function() {


  // permet que la sidebar avec les semestres se répercutes sur les différents cours
  // sur input desemestre on modifie les les inputs invisible
  $("#semestresNav").on('input', 'input', function() {

    var input= $(this).val()
    var name= $(this).attr('name');
    console.log(input);
    console.log(name);

    var id = $(this).closest('li').attr('id'); /* 'tab_3' */
    console.log("id : " +id);
    console.log($(this).closest('li'));

    id = id.substr(id.length - 1) /* Sélection avec l'indice du dernier caractère (substr - 1) : '3' */
    var $list = $('#cursusTableContainer > #tab_' + id + " input[name*='"+name+"']")
    for (var i = 0; i < $list.length; i++) {
      //  console.log("boucle");

      //  var id=$list[i].id
      var s = $list.get(i)
      $(s).attr('value',input);
      //  console.log($(s).attr('value'));

    }

  });

  $("#main_tab.nav-pills").on("click", "a", function(e) {
    e.preventDefault();
    $(this).tab('show');
    $()
  })



// permet la suppression dynamique des semestres
$(".tab-content").on("click", "span", function() {
  var anchor = $(this).siblings('a');
  // console.log(anchor);
  $(anchor.attr('href')).remove();
  $(this).parent().parent().remove();
  $("#sub_tab.nav-pills li").children('a').first().click();
});


// permet la suppression dynamique des row dans les tableaux de semestres
$("#cursusTableContainer").on("click", ".deleteRow", function() {
  var anchor = $(this).siblings('a');
  $(anchor.attr('href')).remove();
  $(this).parent().parent().parent().remove();
  $("#sub_tab.nav-pills li").children('a').first().click();
});



/** Ajout d'un nouveau semestre
*  ===========================
*/
$('#newSemesterButton').on('click', function() {
  /* Création du semestre dans la sidebar */
  var id = $("#semestresNav").children().length;
  var lastSemester = $("#semestresNav input[name=sem_label]").last().val()
  if (lastSemester != undefined) {
    var lastSemesterLabel = lastSemester.match(/([a-zA-Z]+)/gu) /* /gu : /global, /unicode */
    var lastSemesterInt   = lastSemester.match(/([0-9]+)/gu)
    lastSemesterInt = parseInt(lastSemesterInt)
    // console.log("lastSemesterLabel = " + lastSemesterLabel)
    // console.log("lastSemesterInt = " + lastSemesterInt)
  } else {
    var lastSemesterInt   = 0
    var lastSemesterLabel = "ISI"
  }
  var newSemesterContent = `
  <li class="row nav-item" id="form_tab_0` + id + `">
  <input class="form-control form-control-sm col-sm-3"  type="number" min="0" max="8" name="sem_seq" value="` + id + `"> <input class="form-control form-control-sm col-sm-4"  type="text" name="sem_label" placeholder="" value="` + lastSemesterLabel + (lastSemesterInt + 1) + `">
  <div class="col-sm-3 btn-group">
  <a class="btn btn-sm btn-secondary" href="#tab_` + id + `">
  <i class="fa fa-eye" aria-hidden="true"></i>
  </a>
  <a class="btn btn-sm btn-secondary text-danger removeCursus" href="#">
  <i class="fa fa-trash-o" aria-hidden="true"></i>
  </a>
  </div>
  </li>`
  $(this).closest('li').before(newSemesterContent);

  /* Création d'un tableau contenant le cursus */
  var tableHeading = `
  <table class="table" id="tab_` + id + `">
  <thead class="thead-default">
  <tr>
  <th>Sigle</th>
  <th>Crédits</th>
  <th>Affectation</th>
  <th>Catégorie</th>
  <th>Résultat</th>
  <th>UTT</th>
  <th>Profil</th>
  <th>Action</th>
  <th><button class="btn btn-sm btn-outline-success" id="newCoursButton"><i class="fa fa-plus"></i> Cours</button></th>

  </tr>
  </thead>`
  $('#cursusTableContainer').append(tableHeading)
});

/** Suppression d'un semestre de la sidebar
*  =======================================
*  Récupération de l'ID du semestre supprimé puis
*      - suppression du semestre dans la sidebar
*      - suppression du tableau contenant le détail du cursus
*/
$(document).on('click', '.removeCursus', function() {
  var id = $(this).closest('li').attr('id'); /* 'tab_3' */
  id = id.substr(id.length - 1) /* Sélection avec l'indice du dernier caractère (substr - 1) : '3' */
  $(this).closest('li').remove()
  $('#cursusTableContainer > #tab_' + id).remove();
});






/** Création d'un tableau pour un semestre
*  =======================================
*
*/
$('#main_add.add-tab').click(function(e) {
  e.preventDefault();
  var id = $("#main_content  .nav-tabs").children().length;
  $(this).closest('li').before('<li  class="nav-item "><a  id="tab_'+id+'" data-toggle="tab" role="tab" class="nav-link" aria-controls=form_tab_' + id + '"  href="#form_tab_' + id + '">  <div class="row"> <input class="form-control col-md-5" size="7" type="text" name="form[elementsFormations][undefined][sem_seq]" placeholder="N° Sem" value=""><input class="form-control col-md-5" size="7" type="text" name="_sem_label" placeholder="Sem X" value="">  <span class=" col-md-2"><i class="fa fa-times" aria-hidden="true"></i></span></div></a></li>');

  $('#main_content  .tab-content').append(`<div class="tab-pane" id="form_tab_` + id + `" role="tabpanel" ">  ` +` <div class="container table-responsive table">
  <div class="thead">
  <div class="row target" >


  <div class="col th">
  Sigle
  </div>
  <div class="col th">
  Crédits
  </div>

  <div class="col th">
  N° Sem
  </div>
  <div class="col th">
  Label Sem
  </div>
  <div class="col th">
  Affectation
  </div>
  <div class="col th">
  Catégories
  </div>
  <div class="col th">
  Résultats
  </div>
  <div class="col th">
  UTT
  </div>
  <div class="col th">
  profil
  </div>

  </div>
  <div class="col th">
  <button type="submit"  name="form[envoyer]" class="btn btn-sm btn-outline-success bouttonAjouterCours">Ajouter cours</button>
  </div>
  <div class="tbody row">


  </div>

  </div></div>` + `</div>`)


  $('#tab_'+id+' ').trigger('click');
  $('#form_tab_'+id+' .bouttonAjouterCours').trigger('click');
  /* to show particular tab content
  var prvone = $('#main_tab a').length;
  var tabt = $("#main_tab li").eq(prvone - 2).children('a').attr("href");//.tab('show')
  console.log(tabt);
  $(tabt).tab('show');
  */

});

});

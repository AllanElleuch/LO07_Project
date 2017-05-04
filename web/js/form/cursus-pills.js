$( document ).ready(function() {



//$(".slider").slider();
///new tab with remove


$("#main_tab.nav-pills").on("click", "a", function(e) {
    e.preventDefault();
    $(this).tab('show');
    $()
  })

  /*.on("click", "span", function() {
    var anchor = $(this).siblings('a');
    //console.log(anchor);
    $(anchor.attr('href')).remove();
    console.log( $(this).parent().parent().html());
    console.log("yo");
    $(this).parent().parent().remove();

    $("#main_tab.nav-pills li").children('a').first().click();
  });*/

  $("#main_content  .nav-tabs").on("click", "span", function() {
      var anchor = $(this).siblings('a');
      console.log(anchor);
      $(anchor.attr('href')).remove();
      $(this).parent().parent().remove();
      $("#sub_tab.nav-pills li").children('a').first().click();
    });

    $(".tab-content").on("click", "span", function() {
        var anchor = $(this).siblings('a');
        console.log(anchor);
        $(anchor.attr('href')).remove();
        $(this).parent().parent().remove();
        $("#sub_tab.nav-pills li").children('a').first().click();
      });



/** Ajout d'un nouveau semestre
 *  ===========================
 */
$('#newSemesterButton').on('click', function() {
    /* Création du semestre dans la sidebar*/
    var id = $("#semestresNav").children().length;
    var newSemesterContent = `
    <li class="row nav-item" id="form_tab_0` + id + `">
        <input class="form-control form-control-sm col-sm-3"  type="number" min="0" max="8" name="_sem_seq" value="` + id + `"> <input class="form-control form-control-sm col-sm-4"  type="text" name="_sem_label" placeholder="" value="ISI` + id + `">
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

    /* Création d'un tableau contenant le cursus*/
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
    id = id.substr(id.length - 1) /* '3' */
    $(this).closest('li').remove()
    $('#cursusTableContainer > #tab_' + id).remove();
});







$('#main_add.add-tab').click(function(e) {
  e.preventDefault();
  var id = $("#main_content  .nav-tabs").children().length;
  $(this).closest('li').before('<li  class="nav-item "><a  id="tab_'+id+'" data-toggle="tab" role="tab" class="nav-link" aria-controls=form_tab_' + id + '"  href="#form_tab_' + id + '">  <div class="row"> <input class="form-control col-md-5" size="7" type="text" name="_sem_seq" placeholder="N° Sem" value=""><input class="form-control col-md-5" size="7" type="text" name="_sem_label" placeholder="Sem X" value="">  <span class=" col-md-2"><i class="fa fa-times" aria-hidden="true"></i></span></div></a></li>');

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
///end of new tab
/*
$('.f-cal').fullCalendar({
  dayClick: function(date, jsEvent, view) {
    var dateuse = "2016/02/06"; //$.fullCalendar.formatDate(date, 'yyyy-MM-dd');
    alert('Clicked on: ' + dateuse);
    $(this).append('<i class="fa icon fa-user"></i>');

    //intialize sub form tab
    var sub_id = $("#sub_tab.nav-pills").children().length;
    var subfromhtml = ' <div class="subform' + sub_id + '"> <div class="row"> <div class="col-md-12"> <div class="form-group"> <label> Required no of views </label> <input type="text" value="" class="slider form-control" data-slider-max="100" data-slider-step="5" data-slider-value="[70]" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-slider-id="red"> </div><div class="col-md-12 clearfix" style="margin:10px 0" data-toggle="collapse" data-target="#advanced"> <button type="button" id="ads" class="btn btn-default btn-sm">Target Audience</button> </div></div></div><div id="advanced" class="advanced"> <div class="box "> <div class="box-body clearfix"> <div class="row"> <div class="col-md-12"> <div class="form-group"> <label> Age </label> <input type="text"/><input type="text" value="" class="slider" form-control " data-slider-max="100" data-slider-step="5" data-slider-value="[70]" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-slider-id="green"> </div><div class="form-group"> <label> Sex</label> <div class="checkbox"> <label class="checkbox-inline"> <input type="radio" name="sex" value="">Male</label> <label class="checkbox-inline"> <input type="Radio" name="sex" value="">Female</label> </div></div><div class="form-group"> <label> Geography</label> <input id="autocomplete" class=" form-control autocomplete" placeholder="Enter your address" type="text" name="geography"> </div></div></div></div></div></div></div>';

    $("#sub_tab.nav-pills").append('<li><a data-toggle="tab" href="#form_sub_tab_' + sub_id + '">' + dateuse + '</a><span>x</span></li>');
    $('#sub_content.tab-content').append('<div class="tab-pane fade" id="form_sub_tab_' + sub_id + '">' + subfromhtml + '</div>');
    $('#sub_tab a:last').tab('show');
    $(".slider").slider();
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      $(".slider").slider();
    });

    $("#sub_tab.nav-pills").on("click", "a", function(e) {
        e.preventDefault();
        $(this).tab('show');
      })
      .on("click", "span", function() {
        var anchor = $(this).siblings('a');
        console.log(anchor);
        $(anchor.attr('href')).remove();
        $(this).parent().remove();
        $("#sub_tab.nav-pills li").children('a').first().click();
      });

  }
});
*/
});

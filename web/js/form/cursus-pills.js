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

  $("#main_content  .nav-pills").on("click", "span", function() {
      var anchor = $(this).siblings('a');
      console.log(anchor);
      $(anchor.attr('href')).remove();
      $(this).parent().parent().remove();
      $("#sub_tab.nav-pills li").children('a').first().click();
    });


$('#main_add.add-tab').click(function(e) {
  e.preventDefault();
  var id = $("#main_content  .nav-pills").children().length;
  $(this).closest('li').before('<li  class="nav-item "><a  data-toggle="tab" role="tab" class="nav-link" aria-controls=form_tab_' + id + '"  href="#form_tab_' + id + '">Sem' + id + ' <span ><i class="fa fa-times" aria-hidden="true"></i></span></a></li>');
  $('#main_content  .tab-content').append('<div class="tab-pane" id="form_tab_' + id + '"role="tabpanel" "> <div id="accordion" class="accordion" role="tablist"></div></div>');

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

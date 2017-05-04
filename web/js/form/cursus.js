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

    $('.tab-content').on('click', '.bouttonAjouterCours', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        ajouterCours($collectionHolder, $newLinkLi);
    });

    $('.nav-tabs ').on('input', 'input', function() {
        var input= $(this).val()
        var name= $(this).attr('name');
        var list = $(".tab-pane.active .form")
         for (var i = 0; i < list.length; i++) {

             var id=list[i].id
             $("#"+id+name).val(input)
         }
    // get the current value of the input field.
});

    //addTagForm2($collectionHolder, $newLinkLi);
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
    //var $newFormLi = $('<tr></tr>').append(newForm);
    //$newLinkLi.before($newFormLi);
    $(".active .target").append(newForm);
}



function ajouterCours($collectionHolder, $newLinkLi){

  /*
    var list=$(".elemFormation li div .form-control");
    var $id=$collectionHolder.find("li div").get(0).id
    var $form=$collectionHolder.find("#"+$id).clone()
    window.test=$form;

    $(".elemFormation li "+$id).clone().appendTo("#main_content .tab-pane.active .accordion")
    $(".elemFormation li div ").detach();
      var $html=$form;
    var description="";

    var arr = Array();
    i=$($form).find("input").length

    for (var i = 0; i < list.length; i++) {
        arr.push($($form).find("input").get(i));
    }
    $('#example').DataTable().row.add( arr ).draw();


    description+=list[0].value +" : "+list.find("option:selected").get(1).text
    var card= `
    <div class="row">
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
        </div>
        </div>`
        $("#main_content .tab-pane.active .accordion").append(card);
        var $id2="#collapseOne"+$id;
        //$($id2).append($form);
        //$(".elemFormation li").detach().appendTo($($id2))
        $($html).appendTo($($id2))

        addTagForm2($collectionHolder, $newLinkLi);

*/

addTagForm2($collectionHolder, $newLinkLi);
$('.nav-tabs .active input').trigger('input');
}



var dataSet = [
    [ '<input type="text" id="form_elementsFormations_0_sem_label" name="form[elementsFormations][0][sem_label]" required="required" placeholder="ISIx" class="form-control">', "System Architect", "Edinburgh", "5421", "2011/04/25", "$320,800" ],
    [ "Garrett Winters", "Accountant", "Tokyo", "8422", "2011/07/25", "$170,750" ],
    [ "Ashton Cox", "Junior Technical Author", "San Francisco", "1562", "2009/01/12", "$86,000" ],
    [ "Cedric Kelly", "Senior Javascript Developer", "Edinburgh", "6224", "2012/03/29", "$433,060" ],
    [ "Airi Satou", "Accountant", "Tokyo", "5407", "2008/11/28", "$162,700" ],
    [ "Brielle Williamson", "Integration Specialist", "New York", "4804", "2012/12/02", "$372,000" ],
    [ "Herrod Chandler", "Sales Assistant", "San Francisco", "9608", "2012/08/06", "$137,500" ],
    [ "Rhona Davidson", "Integration Specialist", "Tokyo", "6200", "2010/10/14", "$327,900" ],
    [ "Colleen Hurst", "Javascript Developer", "San Francisco", "2360", "2009/09/15", "$205,500" ],
    [ "Sonya Frost", "Software Engineer", "Edinburgh", "1667", "2008/12/13", "$103,600" ],
    [ "Jena Gaines", "Office Manager", "London", "3814", "2008/12/19", "$90,560" ],
    [ "Quinn Flynn", "Support Lead", "Edinburgh", "9497", "2013/03/03", "$342,000" ],
    [ "Charde Marshall", "Regional Director", "San Francisco", "6741", "2008/10/16", "$470,600" ],
    [ "Haley Kennedy", "Senior Marketing Designer", "London", "3597", "2012/12/18", "$313,500" ],
    [ "Tatyana Fitzpatrick", "Regional Director", "London", "1965", "2010/03/17", "$385,750" ],
    [ "Michael Silva", "Marketing Designer", "London", "1581", "2012/11/27", "$198,500" ],
    [ "Paul Byrd", "Chief Financial Officer (CFO)", "New York", "3059", "2010/06/09", "$725,000" ],
    [ "Gloria Little", "Systems Administrator", "New York", "1721", "2009/04/10", "$237,500" ],
    [ "Bradley Greer", "Software Engineer", "London", "2558", "2012/10/13", "$132,000" ],
    [ "Dai Rios", "Personnel Lead", "Edinburgh", "2290", "2012/09/26", "$217,500" ],
    [ "Jenette Caldwell", "Development Lead", "New York", "1937", "2011/09/03", "$345,000" ],
    [ "Yuri Berry", "Chief Marketing Officer (CMO)", "New York", "6154", "2009/06/25", "$675,000" ],
    [ "Caesar Vance", "Pre-Sales Support", "New York", "8330", "2011/12/12", "$106,450" ],
    [ "Doris Wilder", "Sales Assistant", "Sidney", "3023", "2010/09/20", "$85,600" ],
    [ "Angelica Ramos", "Chief Executive Officer (CEO)", "London", "5797", "2009/10/09", "$1,200,000" ],
    [ "Gavin Joyce", "Developer", "Edinburgh", "8822", "2010/12/22", "$92,575" ],
    [ "Jennifer Chang", "Regional Director", "Singapore", "9239", "2010/11/14", "$357,650" ],
    [ "Brenden Wagner", "Software Engineer", "San Francisco", "1314", "2011/06/07", "$206,850" ],
    [ "Fiona Green", "Chief Operating Officer (COO)", "San Francisco", "2947", "2010/03/11", "$850,000" ],
    [ "Shou Itou", "Regional Marketing", "Tokyo", "8899", "2011/08/14", "$163,000" ],
    [ "Michelle House", "Integration Specialist", "Sidney", "2769", "2011/06/02", "$95,400" ],
    [ "Suki Burks", "Developer", "London", "6832", "2009/10/22", "$114,500" ],
    [ "Prescott Bartlett", "Technical Author", "London", "3606", "2011/05/07", "$145,000" ],
    [ "Gavin Cortez", "Team Leader", "San Francisco", "2860", "2008/10/26", "$235,500" ],
    [ "Martena Mccray", "Post-Sales support", "Edinburgh", "8240", "2011/03/09", "$324,050" ],
    [ "Unity Butler", "Marketing Designer", "San Francisco", "5384", "2009/12/09", "$85,675" ]
];

$(document).ready(function() {
    var table = $('#example');
    table.DataTable( {
        data: [],
        columns: [
            { title: "Sigle" },
            { title: "Crédits" },
            { title: "N° Sem" },
            { title: "Label Sem" },
            { title: "Affectation" },
            { title: "Catégories" },
            { title: "Résultats" }
        ]
    } );

    $('#example').DataTable().row.add( [
            "Tiger Nixon",
        "System Architect",
            "$3,120",
            "2011/04/25",
            "Edinburgh",
             "5421",
            "5421",
            "5421"

        ] ).draw();
} );

myApp = {
    'animationNumber' : 0,
    'screenNumber': 1,
    'selectedImages': []
};

$(document).ready(function() 
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#searchBtn').click(function() {
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one checkbox.");
        return false;
      }

    });
    
    $('#doneTagging').click(function(){
        saveTags($("select").data("picker").selected_values());
        setTimeout(window.location.href = '/artistBio', 1000);
    });
    
    $("#saved").hide();
    
    $('.preview-carousel').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        adaptiveHeight: false,
        variableWidth:false,
        arrows:false,
        dots:true,
        responsive: [
            {
              breakpoint: 768,
              settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 3
              }
            },
            {
              breakpoint: 480,
              settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
              }
            }
      ]
      });
});

if(document.getElementById("art_upload") !== null)
{
    document.getElementById("art_upload").onchange = function() {
        document.getElementById("img-upload-form").submit();
    };
}

// initialize the image-picker
$("select").imagepicker({
    changed: function(previousSelection, currentSelection){
        saveTags(previousSelection);
        getTags(currentSelection);
    }
});

$(function() {

    var $sidebar   = $("#tagPanel"), 
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 15;

    $window.scroll(function() {
        if($sidebar.length)
        {
            if ($window.scrollTop() > offset.top) {
                $sidebar.stop().animate({
                    marginTop: $window.scrollTop() - offset.top + topPadding
                });
            } else {
                $sidebar.stop().animate({
                    marginTop: 0
                });
            }
        }
    });
    
});

function getTags(currentSelection)
{
    var myData = 'imageID=' + currentSelection;
    
    jQuery.ajax({
        type: "GET", // HTTP method POST or GET
        url: "getTag", //Where to make Ajax calls
        dataType:"text", // Data type, HTML, json etc.
        data:myData, //Form variables
        success:function(response)
        {
            $(":checkbox").prop('checked', false).parent().removeClass('active');
            var respObj = JSON.parse(response);
            var tag1 = respObj[0].tag1;
            var tag2 = respObj[0].tag2;
            var tag3 = respObj[0].tag3;
            var story = decodeHTML(respObj[0].story);
            
           
            $("#" + tag1).prop('checked', true).parent().addClass('active');
            $("#" + tag2).prop('checked', true).parent().addClass('active');
            $("#" + tag3).prop('checked', true).parent().addClass('active');
            $("#story_text").val(story);
        },
        error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError);
        }
    });
}

function saveTags(currentSelection)
{   
    var selectedTags = ['','',''];
    var story = $("#story_text").val();
        
    for(i = 0; i < $('input:checked').length; i++)
    {
        selectedTags[i] = $('input:checked')[i].id;
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var myData = 'imageID=' + currentSelection + '&tag1=' + selectedTags[0] + 
            '&tag2=' + selectedTags[1] + '&tag3=' + selectedTags[2]+ '&story=' + story; //build a post data structure
    
    jQuery.ajax({
        type: "POST", // HTTP method POST or GET
        url: "saveTag", //Where to make Ajax calls
        dataType:"text", // Data type, HTML, json etc.
        data:myData, //Form variables
        success:function(response){
            //alert('Success');
//            $('#test').text(response);
        },
        error:function (xhr, ajaxOptions, thrownError){
//                    alert(thrownError);
        }
    });
    
    $("#saved").fadeIn();
    $("#saved").fadeOut();
    
    myApp.animationNumber++;
}

function decodeHTML(htmlString) {
    var map = {"gt":">" /* , … */};
    return htmlString.replace(/&(#(?:x[0-9a-f]+|\d+)|[a-z]+);?/gi, function($0, $1) {
        if ($1[0] === "#") {
            return String.fromCharCode($1[1].toLowerCase() === "x" ? parseInt($1.substr(2), 16)  : parseInt($1.substr(1), 10));
        } else {
            return map.hasOwnProperty($1) ? map[$1] : $0;
        }
    });
};

function tagClick(clickedID)
{   
    var limit = 3;
    
    if($('input:checked').length > limit) 
    {
        $("#" + clickedID).prop('checked', false).parent().removeClass('active');
    }
}

$('#image_container').imagesLoaded()
  .always( function( instance ) {
//    console.log('all images loaded');
  })
  .done( function( instance ) {
//    console.log('all images successfully loaded');
  })
  .fail( function() {
//    console.log('all images loaded, at least one is broken');
  })
  .progress( function( instance, image ) {
    var result = image.isLoaded ? 'loaded' : 'broken';
//    console.log( 'image is ' + result + ' for ' + image.img.src );
  });


var $grid = $('.grid').masonry({
    // options
    itemSelector: '.grid-item',
    columnWidth: 150,
    fitWidth: true
});

// layout Masonry after each image loads
$grid.imagesLoaded().progress( function() {
  $grid.masonry('layout');
});

function nextSearchImage(searchID, numberOfScreens, gridArtIDs)
{
    myApp.screenNumber++;
    var myData = '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    for (i = 0; i < $('.grid-item.selected').children().length; i++)
    {
        myApp.selectedImages.push($('.grid-item.selected').children()[i].src);
    }
    
    if (myApp.screenNumber > numberOfScreens)
    {
        myData = saveSelectedImages();
        myData = myData + '&search_id=' + searchID + '&screen_number=' + myApp.screenNumber +
            '&grid_art_ids=' + gridArtIDs + '&number_of_screens=' + numberOfScreens;
        
        $.ajax({
            type: "POST",
            url: "saveSelectedImages",
            dataType:"text", // Data type, HTML, json etc.
            data: myData,
            success: function(response) {   
//                console.log("Screen number js: " + myApp.screenNumber);
                window.location.href = '/searchResults?search_id=' + searchID;
            }
        });
    }
    else
    {
        myData = myData + '&search_id=' + searchID + '&screen_number=' + myApp.screenNumber +
            '&grid_art_ids=' + gridArtIDs + '&number_of_screens=' + numberOfScreens;
        
        $.ajax({
            type: "GET",
            url: "nextSearchImages",
            dataType:"text", // Data type, HTML, json etc.
            data: myData,
            success: function(response) {   
                
                $('#image_container').imagesLoaded()
                .always( function( instance ) {
//                  console.log('all images loaded');
                })
                .done( function( instance ) {
//                  console.log('all images successfully loaded');
                })
                .fail( function() {
//                  console.log('all images loaded, at least one is broken');
                })
                .progress( function( instance, image ) {
                  var result = image.isLoaded ? 'loaded' : 'broken';
//                  console.log( 'image is ' + result + ' for ' + image.img.src );
                });
                
                // update the images selected for the grid
//                $('#artHolderFake').html(response);
                $('#artHolder').html(response);
                
                // re-initialize the image-picker
                $("select").imagepicker();
                
                // re-initialize masonry 
                var grid = $( '.grid' );
                grid.masonry({
                    itemSelector: '.grid-item',
                    columnWidth: 150,
                    fitWidth: true
                });
                
                //reload and layout masonry again
                $(grid).masonry('reloadItems');
                $grid.imagesLoaded().progress( function() {
                    $grid.masonry('layout');
                });
            }
        });
    }
}

function saveSelectedImages()
{
    myData = '';
    
    for (i = 0; i < myApp.selectedImages.length; i++)
    {
        var slashIndex = myApp.selectedImages[i].lastIndexOf('/');
        var imageFileName = myApp.selectedImages[i].substring(slashIndex + 1);
        
        if (i === 0)
        {
            myData = "selected_images=" + imageFileName;
        }
        else
        {
            myData = myData + "," + imageFileName;
        }
    }

    return myData;
}

function saveBehanceImages()
{
    var myData = '';
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    for (i = 0; i < $('.grid-item.selected').children().length; i++)
    {
        myApp.selectedImages.push($('.grid-item.selected').children()[i].src);
    }
    
    myData = myApp.selectedImages;
    myData.toString();
    myData = "selected_images=" + myData;
//    console.log(myData);
    
    $.ajax({
            type: "POST",
            url: "importBehanceImages",
            dataType:"text", // Data type, HTML, json etc.
            data: myData,
            success: function(response) {   
//                console.log(myData);
//                console.log("Screen number js: " + myApp.screenNumber);
                window.location.href = '/homeBehanceImport';
            }
    });
}

function deletePicClick(clickedID)
{   
    var myData = 'imageID=' + $("select").data("picker").selected_values(); //build a post data structure
        
    jQuery.ajax({
        type: "POST", // HTTP method POST or GET
        url: "deletePic", //Where to make Ajax calls
        dataType:"text", // Data type, HTML, json etc.
        data:myData, //Form variables
        success:function(response)
        {
            window.location.href = '/tagArt';
//            console.log("deleted \n");
//            console.log(response);
        },
        error:function (xhr, ajaxOptions, thrownError){
            console.log(thrownError);
        }
    });
}
    
function nextBrowsePage(numberOfScreens, gridArtIDs, imagePaths, view)
{
    myApp.screenNumber++;
    var myData = '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (myApp.screenNumber > numberOfScreens)
    {
        if(view === 1)
        {
            window.location.href = '/browseArt';
        }
        else if(view === 2)
        {
            window.location.href = '/home';
        }
        else if(view === 3)
        {
            saveTags($("select").data("picker").selected_values());
            setTimeout(window.location.href = '/tagArt', 1000);
        }
    }
    else
    {
        // clear tag selections
        resetTags();
        
        myData = myData + '&screen_number=' + myApp.screenNumber +
            '&grid_art_ids=' + gridArtIDs + '&number_of_screens=' + numberOfScreens
            + '&image_paths=' + imagePaths;

        $.ajax({
            type: "GET",
            url: "nextBrowsePage",
            dataType:"text", // Data type, HTML, json etc.
            data: myData,
            success: function(response) {   

                $('#image_container').imagesLoaded()
                .always( function( instance ) {
//                  console.log('all images loaded');
                })
                .done( function( instance ) {
//                  console.log('all images successfully loaded');
                })
                .fail( function() {
//                  console.log('all images loaded, at least one is broken');
                })
                .progress( function( instance, image ) {
                  var result = image.isLoaded ? 'loaded' : 'broken';
//                  console.log( 'image is ' + result + ' for ' + image.img.src );
                });

                // update the images selected for the grid
//                $('#artHolderFake').html(response);
                $('#artHolder').html('<option value="0"></option>' + response);

                // re-initialize the image-picker
                $("select").imagepicker({
                    changed: function(previousSelection, currentSelection){
                        saveTags(previousSelection);
                        getTags(currentSelection);
                    }
                });

                // re-initialize masonry 
                var grid = $( '.grid' );
                grid.masonry({
                    itemSelector: '.grid-item',
                    columnWidth: 150,
                    fitWidth: true
                });

                //reload and layout masonry again
                $(grid).masonry('reloadItems');
                $grid.imagesLoaded().progress( function() {
                    $grid.masonry('layout');
                });
            },
            error:function (xhr, ajaxOptions, thrownError){
                console.log(thrownError);
            }
        });
    }
}

function resetTags()
{
    $(":checkbox").prop('checked', false).parent().removeClass('active');
}

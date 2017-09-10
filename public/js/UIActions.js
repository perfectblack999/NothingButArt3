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
});

if(document.getElementById("art_upload") != null)
{
    document.getElementById("art_upload").onchange = function() {
        document.getElementById("img-upload-form").submit();
    };
}

function getTags(state)
{
    var str = $('#slide-list.flex-active-slide').children()[0].src.toString(); 
    var revStr = str.split("").reverse().join("");
    var picFile = str.substring(str.length - revStr.indexOf('/'), str.length);
    var myData = 'fileName=' + picFile; //build a post data structure
        
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
           
            $("#" + tag1).prop('checked', true).parent().addClass('active');
            $("#" + tag2).prop('checked', true).parent().addClass('active');
            $("#" + tag3).prop('checked', true).parent().addClass('active');
        },
        error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError);
        }
    });
}

function tagClick(clickedID)
{   
    var limit = 3;
    
    if($('input:checked').length > limit) 
    {
        $("#" + clickedID).prop('checked', false).parent().removeClass('active');
    }
}

function saveTags()
{   
    var str = $('#slide-list.flex-active-slide').children()[0].src.toString();
    var revStr = str.split("").reverse().join("");
    var picFile = str.substring(str.length - revStr.indexOf('/'), str.length);
    var selectedTags = ['','',''];
    
//    console.log("Pre: " + picFile);
    
    for(i = 0; i < $('input:checked').length; i++)
    {
        selectedTags[i] = $('input:checked')[i].id;
//        console.log("Selected Tag " + i.toString() + " " + selectedTags[i]);
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var myData = 'fileName=' + picFile + '&tag1=' + selectedTags[0] + 
            '&tag2=' + selectedTags[1] + '&tag3=' + selectedTags[2]; //build a post data structure
    
    jQuery.ajax({
        type: "POST", // HTTP method POST or GET
        url: "saveTag", //Where to make Ajax calls
        dataType:"text", // Data type, HTML, json etc.
        data:myData, //Form variables
        success:function(response){
            //alert('Success');
            $('#test').text(response);
        },
        error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError);
        }
    });
    
    myApp.animationNumber++;
}

$(window).load(function(){
  $('#carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
    itemMargin: 5,
    asNavFor: '#slider'
  });

  $('#slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel",
    before:function(){
        saveTags();
    },
    after:function(){
        getTags();
    },
    start: function(slider){
        $('body').removeClass('loading');
        getTags();
    }
  });
});

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

// initialize the image-picker
$("select").imagepicker();
    
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
//                console.log("next screen");
//                console.log("Screen number js: " + myApp.screenNumber);
//                console.log(response);
                
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
    console.log(myData);
    
    $.ajax({
            type: "POST",
            url: "importBehanceImages",
            dataType:"text", // Data type, HTML, json etc.
            data: myData,
            success: function(response) {   
                console.log(myData);
//                console.log("Screen number js: " + myApp.screenNumber);
                window.location.href = '/homeBehanceImport';
            }
    });
}

function deletePicClick(clickedID)
{   
    var str = $('#slide-list.flex-active-slide').children()[0].src.toString();
    var revStr = str.split("").reverse().join("");
    var picFile = str.substring(str.length - revStr.indexOf('/'), str.length);
    var myData = 'fileName=' + picFile; //build a post data structure
    console.log(myData);
        
    jQuery.ajax({
        type: "POST", // HTTP method POST or GET
        url: "deletePic", //Where to make Ajax calls
        dataType:"text", // Data type, HTML, json etc.
        data:myData, //Form variables
        success:function(response)
        {
            console.log("deleted \n");
            console.log(response);
        },
        error:function (xhr, ajaxOptions, thrownError){
            console.log(thrownError);
            console.log(response);
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
            window.location.href = '/home'
        }
    }
    else
    {
        myData = myData + '&screen_number=' + myApp.screenNumber +
            '&grid_art_ids=' + gridArtIDs + '&number_of_screens=' + numberOfScreens
            + '&image_paths=' + imagePaths;

        $.ajax({
            type: "GET",
            url: "nextBrowsePage",
            dataType:"text", // Data type, HTML, json etc.
            data: myData,
            success: function(response) {   
//                console.log("next screen");
//                console.log("Screen number js: " + myApp.screenNumber);
//                console.log(response);

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
            },
            error:function (xhr, ajaxOptions, thrownError){
                console.log(thrownError);
                console.log(response);
            }
        });
    }
}

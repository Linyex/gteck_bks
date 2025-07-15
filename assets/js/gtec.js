/** 
 * Mobile-menu v. 1.0.0
 * Copyright 2022 Cbiluxa
 **/

$('.menu-btn ').on('click', function (e) {
    e.preventDefault;
    $(this).toggleClass('active');
    $('.side-panel').toggleClass('active');
    $('.btn-close').toggleClass('active');
    document.getElementById('close-overlay').style.display = 'block';

    $('#close-overlay').click(function () {
        $('#close-overlay').css({
            'display': 'none'
        });
        $('.menu-btn').removeClass('active');
        $('.side-panel.active').removeClass('active');
        $('.btn-close.active').removeClass('active');
    });

    $('.btn-close.active').on('click', function (e) {
        e.preventDefault;
        $(this).removeClass('active');
        $('.menu-btn').removeClass('active');
        $('.side-panel.active').removeClass('active');
        document.getElementById('close-overlay').style.display = 'none';
    });
});


$('#filtr').on('click', function (e) {
    e.preventDefault;
    $('.filtr').toggleClass('active');


})

$('#st-button').on('click', function (e) {
    e.preventDefault;
    $('.st-dropdown').toggleClass('active');
    $('.st-dropdown-bottom').toggleClass('active');
})

/* LOGIN- MODAL*/

$('#side-login').on('click', function (e) {
    e.preventDefault;
    $('.side-login').toggleClass('active');
    $('.btn-close2').toggleClass('active');
    document.getElementById('close-overlay').style.display = 'block';

    $('#close-overlay').click(function () {
        $('#close-overlay').css({
            'display': 'none'
        });
        $('.side-login.active').removeClass('active');
        $('.btn-close2.active').removeClass('active');
    });

    $('.btn-close2.active').on('click', function (e) {
        e.preventDefault;
        $(this).removeClass('active');
        $('.side-login.active').removeClass('active');
        document.getElementById('close-overlay').style.display = 'none';
    });
});

$('#mobile-side-login').on('click', function (e) {
    e.preventDefault;
    $('.side-panel.active').removeClass('active');
    $('.btn-close.active').removeClass('active');
    document.getElementById('close-overlay').style.display = 'none';

    $('.side-login').toggleClass('active');
    $('.btn-close2').toggleClass('active');
    document.getElementById('close-login').style.display = 'block';

    $('#close-login').click(function () {
        $('#close-login').css({
            'display': 'none'
        });
        $('.side-login.active').removeClass('active');
        $('.btn-close2.active').removeClass('active');
    });

    $('.btn-close2.active').on('click', function (e) {
        e.preventDefault;
        $(this).removeClass('active');
        $('.side-login.active').removeClass('active');
        document.getElementById('close-login').style.display = 'none';
    });
});


var m_id = new Array('s_mn_1', 's_mn_2', 's_mn_3', 's_mn_4', 's_mn_5', 's_mn_6', 's_mn_7', 's_mn_8', 's_mn_9', 's_mn_10');
listStart = function allclose() {
    for (i = 0; i < m_id.length; i++) {
        var ela = document.getElementById(m_id[i]);
        if (ela) {
           document.getElementById(m_id[i]).style.display = "none"; 
        }
    }
}

function menuOpen(id) {
    // for (i = 0; i < m_id.length; i++) {
    //     if (id != m_id[i]) {
    //         document.getElementById(m_id[i]).style.display = "none";
    //     }
    // }
    if (document.getElementById(id).style.display == "block") {
        document.getElementById(id).style.display = "none";
    } else {
        document.getElementById(id).style.display = "block";
    }
}
window.onload = listStart;


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var ppa = document.getElementById("bloa");
        var blockw = document.getElementById("block-view");
        blockw.setAttribute('style', 'display: block');
        
        var blockupla= document.getElementById("prosto");
        blockupla.setAttribute('style', 'margin-top: 30px; width: 100%;');
        
        reader.onload = function (e) {
            ppa.setAttribute('href', e.target.result);
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#filephoto").change(function(){
    readURL(this);
});

function handleFileSelectMulti(evt) {
    var files = evt.target.files; // FileList object
    for (var i = 0, f; f = files[i]; i++) {
        if (!f.type.match('image.*')) {
            alert("Нельзя загружать скрипты!");
        } 
    }
}

var elt = document.getElementById('uploadbtn');

if (elt) {
    document.getElementById('uploadbtn').addEventListener('change', handleFileSelectMulti, false);
}


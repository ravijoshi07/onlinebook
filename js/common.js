var PathUrl = '';
var authWindow;
var isAdmin = false;
var AJAX_LOCK = false;
var $modal;
var SELECTOR;
var SELECTORVAL = 0;
var cwin;
var m_names = new Array("January", "February", "March","April", "May", "June", "July", "August", "September", "October", "November", "December");
var d_names = new Array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
var toptions = {"00:30:00": "12:30 am", "01:00:00": "01:00 am", "01:30:00": "01:30 am", "02:00:00": "02:00 am", "02:30:00": "02:30 am", "03:00:00": "03:00 am", "03:30:00": "03:30 am", "04:00:00": "04:00 am", "04:30:00": "04:30 am", "05:00:00": "05:00 am", "05:30:00": "05:30 am", "06:00:00": "06:00 am", "06:30:00": "06:30 am", "07:00:00": "07:00 am", "07:30:00": "07:30 am", "08:00:00": "08:00 am", "08:30:00": "08:30 am", "09:00:00": "09:00 am", "09:30:00": "09:30 am", "10:00:00": "10:00 am", "10:30:00": "10:30 am", "11:00:00": "11:00 am", "11:30:00": "11:30 am", "12:00:00": "12:00 pm", "12:30:00": "12:30 pm", "13:00:00": "01:00 pm", "13:30:00": "01:30 pm", "14:00:00": "02:00 pm", "14:30:00": "02:30 pm", "15:00:00": "03:00 pm", "15:30:00": "03:30 pm", "16:00:00": "04:00 pm", "16:30:00": "04:30 pm", "17:00:00": "05:00 pm", "17:30:00": "05:30 pm", "18:00:00": "06:00 pm", "18:30:00": "06:30 pm", "19:00:00": "07:00 pm", "19:30:00": "07:30 pm", "20:00:00": "08:00 pm", "20:30:00": "08:30 pm", "21:00:00": "09:00 pm", "21:30:00": "09:30 pm", "22:00:00": "10:00 pm", "22:30:00": "10:30 pm", "23:00:00": "11:00 pm", "23:30:00": "11:30 pm"};
var sitename = $('meta[property="og:site_name"]').attr('content');
var sitelogo = $('meta[property="logo"]').attr('content');
var GETNEWS = false;
(function ($) {
    
    $.common = {
        settings: [],
        pagUrl: null,
        ajaxlock: false,
        init: function (s) {            
            $.common.settings = s;
            PathUrl = s.baseUrl;

            if (s.admin)
                isAdmin = s.admin;

            
            var $modal = $('#ajaxModel'); 

            $(document).on('click', 'a.popuplink', function () {                
                
                var url = $(this).attr('href');
                if ($modal.is(':visible')) {
                    $modal.modal('destroy');
                }
                $('body').modalmanager('loading');
                var datawidth = null;
                if ($(this).attr('data-width')) {
                    datawidth = $(this).attr('data-width');
                }

                $modal.load(url, '', function(e,d){
                    
                    if(d!='error')
                        $modal.modal({width:datawidth});                    

                    if(url.indexOf("login")!=-1)
                        FB.XFBML.parse();

                });
                return false;
            });

            $(document).on('submit', 'form.ajax-form', function (e) {
                var frm = this;
                $.ajax({
                    type: 'POST',
                    url: $(frm).attr("action"),
                    data: $(frm).serialize(),
                    success: function (data) {
                        $modal.html(data);
                        $modal.modal('removeLoading');
                        $(window).trigger('resize');
                    },
                    beforeSend: function () {
                        //$(frm).find('button[type="submit"]').button('loading');
                        $modal.modal('loading');
                    }
                });
                return false;
            });           

            $(document).on('click', 'form button[type="submit"]', function () {
                
                /*if($(this).attr('data-loading-text')=='')
                    $(this).attr('data-loading-text', '..');*/
                
                if($(this).attr('data-loading-text'))
                        $(this).button('loading');
                
            });
	    
	    
    	    $(document).on('pjax:send', function(e) {              
                var selector = $(e.target);  
                selector.addClass('is-loading');                
            });
    	    
    	    $(document).on('pjax:complete', function(e) {
                var selector = $(e.target);
                selector.removeClass('is-loading');
                
                if(selector.attr('id')!='newsletterform')
                    selector.scrollTo(500);

                if($('#addusercom').length){
                    $('#addusercom').button('reset');
                }
            });
    	    
    	    $(document).on('pjax:error', function(e,textStatus,error) {              
                
                if(textStatus.status == 406){                
                    alert('Cannot enable more than 5 stories. Please disable any featured story.');
                    return false;
                }

                if(textStatus.status == 404 || textStatus.status == 500){                
                    alert('Something went wrong.');
                }
                /*var url = document.location.href;
                var urlparts = url.split('?');
                window.location.href = urlparts;*/
                return false;
            });
    			
    		$(document).on('click','.shareme',function(e){
                e.preventDefault();
    			var elem = $(this);
    			var hiturl = '';
                var addurl = true;					
    			//FB
    			if(elem.attr('data-pro') == 'fb'){
    				
                    if(elem.attr('data-type') == 'comment'){
                        hiturl = 'https://www.facebook.com/dialog/feed?'+
                                  '&display=popup&caption='+sitename+
                                  '&name=Comment by '+encodeURI(elem.attr('name'))+' on '+sitename+
                                  '&description='+encodeURI(elem.attr('description'))+
                                  '&picture='+encodeURI(sitelogo)+
                                  '&app_id=724033927729690&redirect_uri='+encodeURI(window.location.href)+'&link=';

                    }else{
                        hiturl = 'https://www.facebook.com/sharer/sharer.php?u=';
                    }
    			}else if(elem.attr('data-pro') == 'tw'){
    				if(elem.attr('data-type') == 'comment'){
                        addurl = false;
                        hiturl = 'https://twitter.com/home?status='+elem.attr('description')+' : '+$('meta[itemprop="shorturl"]').attr('content');
                    }else{
                        hiturl = 'https://twitter.com/home?status='+$('meta[property="og:site_name"]').attr('content')+' : ';
                    }
    			}else if(elem.attr('data-pro') == 'gp'){
    				hiturl = 'https://plus.google.com/share?url=';
    			}else if(elem.attr('data-pro') == 'pin'){
    				if(elem.attr('data-type') == 'comment'){
                        hiturl = 'https://pinterest.com/pin/create/button/?media='+encodeURI(sitelogo)+'&description='+elem.attr('description')+'&url=';
                    }else{
                        hiturl = 'https://pinterest.com/pin/create/button/?media='+encodeURI(elem.attr('d-media'))+'&description='+elem.attr('d-desc')+'&url=';
                    }
    			}else{
                    return 1;
                }					
    			
    			if(hiturl!=''){
    				var surl = elem.attr('data-url');
    				if(typeof(surl)!='undefined')
    					surl = encodeURI(elem.attr('data-url'));
    				else
    					surl = $('meta[property="og:url"]').attr('content');
    				
    				if(addurl)
                        hiturl = hiturl+surl;
    				
                    var param = elem.attr('data-param');

                    if(typeof(param)!='undefined'){
                        hiturl = hiturl+param;    
                    }
                    //console.log(hiturl);

                    $.common.WINPOPUP(e,hiturl);
    			}
    		});	


            $(document).on('change','.singlecheck',function(){
                if ($(this).is(':checked')) {
                    $('.singlecheck').prop('checked', false);
                    $(this).prop('checked', true);
                    var ind = $('.singlecheck').index(this);
                    $('.defaultIndex').val(ind);                    
                }
            });				
		    

        },
        showLoading: function () {
            $('body').modalmanager('loading');
        },
        WINPOPUP: function (e, url) {
            e.preventDefault();
            var output = 'Please Wait..';
            var width = 575,
                    height = 400,
                    left = ($(window).width() - width) / 2,
                    top = ($(window).height() - height) / 2,
                    opts = 'scrollbars=1,resizable=1,status=1' + ',width=' + width + ',height=' + height + ',top=' + top + ',left=' + left;
            authWindow = window.open('about:blank', '', opts);
            authWindow.document.write(output);
            if (url) {
                //if(url.indexOf("http:")<0)
                //url = PathUrl+"/"+url;

                authWindow.location.replace(url);
            }

            /*cwin = setInterval(function(){
                if (authWindow.location==null) {
                    clearInterval(cwin);
                    alert(url);
                    $('body').modalmanager('loading');
                }
            },0);*/

            

            return;
        }
    }

    $(document).on("click",".deal_like",function(e){
        var deal_id=$(this).attr("data-id");
        var like_status=$(this).attr("data-status");
        var current_likes=$(this).find("span").html();
        $.ajax({
            type: "POST",
            url: PathUrl+"change-like-status",
            data: {"id":deal_id,"status":like_status},
            dataType: "json",
            success: function(result){
                if(result.status=="success"){
                    if(like_status=="Like"){
                        $("."+deal_id+"_like_button").addClass('fa-heart');
                        $("."+deal_id+"_like_button").removeClass('fa-heart-o');
                        $("."+deal_id+"_like_button").attr('data-status',"Dislike");
                        $("."+deal_id+"_like_button").find("span").html(parseInt(current_likes)+1);

                    }else{
                        $("."+deal_id+"_like_button").removeClass('fa-heart');
                        $("."+deal_id+"_like_button").addClass('fa-heart-o');
                        $("."+deal_id+"_like_button").attr('data-status',"Like");
                        $("."+deal_id+"_like_button").find("span").html(parseInt(current_likes)-1);
                    }
                    
                }else if(result.status=="login_error"){
                     window.location = PathUrl+"site/login";
                }else{
                    alert("Error Occoured")
                }
            }
        });
    });    

    $(document).on("click",".mailto",function(e){
        e.preventDefault();
        var share_link=$(this).attr("data-url");
        var deal_title=$(this).attr("data-title");
        $("#mailsharetitle").html(deal_title);
        $("#mailshareurl").html(share_link);
        $('#mailshare').modal('show');
        
    });
    
})(jQuery);
 
$(document).ajaxComplete(function (event, xhr, settings) {
    var url = xhr.getResponseHeader('X-Redirect');
    if (url) {
        window.location = url;
    }
});



function ucfirst(str) {
    //  discuss at: http://phpjs.org/functions/ucfirst/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Onno Marsman
    // improved by: Brett Zamir (http://brett-zamir.me)
    //   example 1: ucfirst('kevin van zonneveld');
    //   returns 1: 'Kevin van zonneveld'

    str += '';
    var f = str.charAt(0)
            .toUpperCase();
    return f + str.substr(1);
}

function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    $.cookie("latitude", latitude);
    $.cookie("longitude", longitude);
    location.reload();
}

jQuery.fn.extend({
    scrollTo: function (speed, easing) {
        return this.each(function () {
            var targetOffset = $(this).offset().top;
            $('html,body').animate({scrollTop: targetOffset}, speed, easing);
        });
    }
});

// $(function() {    
//     $('img.imloader').lazy({
//         afterLoad:function(element) {
//             element.removeClass('imloader');
//         }
//     });    
// });

function addmoreimages(){
    var newFile = '<div class="col-md-3  addimages">'+
                    '<div class="fileinput fileinput-new" data-provides="fileinput">'+
                        '<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 100px;">'+                            
                        '</div>'+                        
                        '<div>'+
                            '<span class="btn btn-default btn-file">'+
                                '<span class="fileinput-new">Select image</span>'+
                                '<span class="fileinput-exists">Change</span>'+
                                '<input type="file" accept="image/*"name="Deal[otherdealimage][]">'+
                            '</span>'+
                            '<span>'+
                                '<button class="btn btn-default" type="button" onClick="$(this).closest(\'div.addimages\').remove();">'+
                                    '<span class="fa fa-trash"></span>'+
                                '</button>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+                    
                    '</div>';

    $('#images').append(newFile);
                  
}
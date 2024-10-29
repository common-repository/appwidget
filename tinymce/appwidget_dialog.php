<?php
// look up for the path
require_once(dirname(__FILE__).'/wpload.php');
require_once(ABSPATH.'wp-admin/admin.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>分享应用</title>
<script type="text/javascript" src= "<?php get_bloginfo('wpurl');?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<?php
    wp_enqueue_style( 'colors');
    do_action('admin_print_styles');
    do_action('admin_print_scripts');
?>
<style type="text/css">
  .aw-wrap{padding: 10px; background: #fff; min-height: 430px;}
  .aw-search-wrap{text-align: center; margin:15px 0;}
  .loading{text-align: center;}
  .aw-iframe-preview{border-top:1px dotted #dfdfdf; padding:15px 5px 5px 5px; }
  .previewwrap{min-height: 20px;padding: 10px 10px 0px 10px;background-color: whiteSmoke;border: 1px solid #EEE;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);}
</style>
</head>
<body>
<div class="aw-wrap">
  <div class="aw-search-wrap">
      <input  id="searchBox" style="width:200px;" type="search" autocomplete="off" placeholder="搜索软件/游戏" value="" required=""/>
      <button class="button" id="searchBtn" type="button">搜索</button>
  </div>
    <p class="loading" style="display:none">努力搜索中.....</p>
    <div id="media-items">
      <p style="text-align: center;margin-top:20px;"><img src="<?php get_bloginfo('wpurl');?>/wp-content/plugins/appwidget/tinymce/images/banner.jpg" alt="从应用汇发现更多好玩有趣的应用!"/></p>
    </div>
</div>
<script type="text/javascript">
jQuery(function(){
 var html = '<div class="aw-iframe-preview"><div style="margin-bottom: 15px;"><label><input type="radio" name="optionsRadios" id="optionsRadios3" value="3" checked="checked"> 通栏式</label> <label><input type="radio" name="optionsRadios" id="optionsRadios1" value="1"> 经典式</label> <label><input type="radio" name="optionsRadios" id="optionsRadios2" value="2"> 现代式</label> <label><input type="radio" name="optionsRadios" id="optionsRadios4" value="4"> 按钮式</label> <input id="insertBtn" type="submit" style="float: right;" class="button-primary" value="插入到文章" /></div><div class="previewwrap"><div id="previewBox"><iframe id="previewIframe" frameborder="0" scrolling="no"></iframe></div></div></div>'	
 //绑定回车键
 jQuery(document).keydown(function(e){
    if(e.keyCode == 13) {
      jQuery('#searchBtn').click();
      }
  });
  //搜索
  jQuery('#searchBtn').click(function(){
    var inputVal = jQuery('#searchBox').val();
    if(!inputVal){
      alert('请先输入搜索关键字！')
    }else{
     jQuery.ajax({
          url: "http://i.appchina.com/app/queryp",
          dataType: "jsonp",
          data:{
                 q:inputVal,
                 size:10
                },
          jsonp : 'p',
          crossDomain : true,
          beforeSend:function(){
            jQuery('.loading').show();
          },
          complete:function(){
            jQuery('.loading').hide();
          },
          success: function(data){
            console.log(data);
            jQuery('#media-items').html('');
            jQuery(data).each(function(i){
              var data_html = '<li>'+data[i].name+'</li>';
              var data_html ='<div class="media-item "><img class="pinkynail" src="http://appchina.com/icon/'+data[i].packageName+'"  alt=""><a class="toggle describe-toggle-on" href="#">选择版式</a><a class="toggle describe-toggle-off" href="#">收起</a><div class="filename new"><span class="title">'+data[i].name+'</span></div><input type="hidden" id="'+data[i].id+'" value="'+data[i].packageName+'"></div>'
              jQuery('#media-items').append(data_html);
            });
          }
      });
    };
  });
  //展开预览widget
  jQuery('.toggle:contains("选择版式")').live('click',function(){
      var pn = jQuery(this).siblings("input[type='hidden']").val();
      jQuery(this).parent().siblings().find('.aw-iframe-preview').remove();
      jQuery(this).parent().siblings().find('.describe-toggle-off').hide();
      jQuery(this).parent().siblings().find('.describe-toggle-on').show();
      jQuery(this).hide();
      jQuery(this).siblings('a').show();
      jQuery(this).parent().append(html);
      jQuery("#previewIframe").attr({
              height: "125px",
              width: "100%",
              src:"http://i.appchina.com/widget/"+pn+"/3"
            });
      jQuery("body").animate({scrollTop: jQuery(this).parent().offset().top},500);
      return false;
  });
  //隐藏预览widget
  jQuery('.toggle:contains("收起")').live('click',function(){
      jQuery(this).hide();
      jQuery(this).siblings('a').show();
      jQuery(this).parent().find('.aw-iframe-preview').remove();
      return false;
  });
  //选择样式
  jQuery("input[name='optionsRadios']").live('change',function(){
    var pn = jQuery(this).parents().find("input[type='hidden']").val();
    var type = jQuery('input:checked').val();
     switch (type) {
          case '1': 
              jQuery("#previewIframe").attr({
                height: "135px",
                width: "320px",
                src:"http://i.appchina.com/widget/"+pn+"/1"
              }); 
            break;
          case '2':
              jQuery("#previewIframe").attr({
                height: "245px",
                width: "200px",
                src:"http://i.appchina.com/widget/"+pn+"/2"
              });
            break;
          case '3':
              jQuery("#previewIframe").attr({
                height: "125px",
                width: "100%",
                src:"http://i.appchina.com/widget/"+pn+"/3"
              });
            break;
          case '4':
              jQuery("#previewIframe").attr({
                height: "130px",
                width: "190px",
                src:"http://i.appchina.com/widget/"+pn+"/4"
              });
            break;
        } 
  });
  //插入到文章
  jQuery('#insertBtn').live('click',function(){
    AppWidgetDialog.insert(AppWidgetDialog.local_ed);
    return false;
  });
});
//tinymce
var AppWidgetDialog = {
  local_ed: 'ed',
  init: function ( ed ) {
    AppWidgetDialog.local_ed = ed;
    tinyMCEPopup.resizeToInnerSize ();
  },
  insert: function insertHighlightSection ( ed ) {
    try {
      var output = document.getElementById('previewBox').innerHTML;
      AppWidgetDialog.local_ed.selection.setContent ( output );
      tinyMCEPopup.close ();
    }
    catch ( e ) {
      alert ( e );
    }
  }
};
tinyMCEPopup.onInit.add ( AppWidgetDialog.init, AppWidgetDialog );
</script>
</body>
</html>
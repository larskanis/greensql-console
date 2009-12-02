function init_help()
{
var help_win = document.getElementById('help_content');
var show_win = document.getElementById('show_help');
var show = getCookie('show_help');
if (!help_win || !show_win)
{
  return;
}

if (show == 1 || show == "")
{
  setCookie('show_help','1',10);
  if (help_win && show_win)
  {
    help_win.style.display='block';
    show_win.style.display='none';
  }
} else {
  setCookie('show_help','0',10);
  if (help_win && show_win)
  {
    help_win.style.display='none';
    show_win.style.display='block';
  }
}
}

function setCookie(c_name,value,expiredays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate()+expiredays);
document.cookie=c_name+ "=" +escape(value)+
 ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=")
  if (c_start!=-1)
    { 
    c_start=c_start + c_name.length+1 
    c_end=document.cookie.indexOf(";",c_start)
    if (c_end==-1) c_end=document.cookie.length
    return unescape(document.cookie.substring(c_start,c_end))
    } 
  }
return "";
}

function show_help()
{
var help_win = document.getElementById('help_content');
var show_win = document.getElementById('show_help');

help_win.style.display='block';
show_win.style.display='none';
setCookie('show_help','1',10);
}

function hide_help()
{
var help_win = document.getElementById('help_content');
var show_win = document.getElementById('show_help');

help_win.style.display='none';
show_win.style.display='block';
setCookie('show_help','0',10);
}

function ShowProxy(tokenname,tokenid){
    var ref = "";
    if(document.getElementsByName("proxyid")[0].value == 0)
        ref = 'db_add.php?type=newproxy&' + tokenname + '=' + tokenid;            
    else ref = 'db_add.php?type=newdb&proxyid=' + document.getElementsByName("proxyid")[0].value + '&' + tokenname + '=' + tokenid;            
    location.href = ref;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function setImageSrc(nameImg, srcImg)
{
    document.images[nameImg].src = srcImg;
}
/*
function swapButtonMore(nameImg, statusOn)
{
    srcImg = 'graphics/btn-more' + (statusOn ? '_on' : '') + '.png';
    setImageSrc(nameImg, srcImg);
}
*/

ns4 = document.layers;
ie4 = document.all;
nn6 = document.getElementById && !document.all;
var NS = (navigator.appName=="Netscape")?true:false;

function FitPic() {
  iWidth = (NS)?window.innerWidth:document.body.clientWidth;
  iHeight = (NS)?window.innerHeight:document.body.clientHeight;
  iWidth = document.images[0].width - iWidth;
  iHeight = document.images[0].height - iHeight;
  window.resizeBy(iWidth, iHeight);
  self.focus();
};

function showObject(idObj) {

  if (ns4) {
     document.idObj.visibility = "show";
  }
  else if (ie4) {
     document.all[idObj].style.visibility = "visible";
  }
  else if (nn6) {
     document.getElementById(idObj).style.visibility = "visible";
  }
}

function showObjectXy(idObj, e, offsetX, offsetY) {

  if (ns4) {
     document.idObj.visibility = "show";
     document.idObj.left = e.pageX + offsetX;
     document.idObj.top = e.pageY + offsetY;
  }
  else if (ie4) {
     document.all[idObj].style.visibility = "visible";
     document.all[idObj].style.left = e.pageX + offsetX;
     document.all[idObj].style.top = e.pageY + offsetY;
  }
  else if (nn6) {
     document.getElementById(idObj).style.visibility = "visible";
     document.getElementById(idObj).style.left = e.pageX + offsetX + "px;";
     document.getElementById(idObj).style.top = e.pageY + offsetY + "px;";
  }
}

function hideObject(idObj) {
  if (ns4) {
     document.idObj.visibility = "hide";
  }
  else if (ie4) {
     document.all[idObj].style.visibility = "hidden";
  }
  else if (nn6) {
     document.getElementById(idObj).style.visibility = "hidden";
  }
}

function toggleChoice(idObj)
{
    obj = document.getElementById(idObj);
    obj.checked = ! obj.checked;
}

// JavaScript Document
var b2Speed = 50; //速度(毫秒)
var b2Space = 40; //每次移动(px)
var b2PageWidth =198; //翻页宽度
var b2fill = 0; //整体移位
var b2MoveLock = false;
var b2MoveTimeObj;
var b2Comp = 0;
var b2AutoPlayObj = null;
b2GetObj("b2List2").innerHTML = b2GetObj("b2List1").innerHTML;
b2GetObj('b2ISL_Cont').scrollLeft = b2fill;
b2GetObj("b2ISL_Cont").onmouseover = function(){clearInterval(b2AutoPlayObj);}
b2GetObj("b2ISL_Cont").onmouseout = function(){b2AutoPlay();}
b2AutoPlay();
function b2GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval('document.all.'+objName)}}
function b2AutoPlay(){ //自动滚动
 clearInterval(b2AutoPlayObj);
 b2AutoPlayObj = setInterval('b2ISL_GoDown();b2ISL_StopDown();',8000); //间隔时间
}
function b2ISL_GoUp(){ //上翻开始
 if(b2MoveLock) return;
 clearInterval(b2AutoPlayObj);
 b2MoveLock = true;
 b2MoveTimeObj = setInterval('b2ISL_ScrUp();',b2Speed);
}
function b2ISL_StopUp(){ //上翻停止
 clearInterval(b2MoveTimeObj);
 if(b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth - b2fill != 0){
  b2Comp = b2fill - (b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth);
  b2CompScr();
 }else{
  b2MoveLock = false;
 }
 b2AutoPlay();
}
function b2ISL_ScrUp(){ //上翻动作
 if(b2GetObj('b2ISL_Cont').scrollLeft <= 0){b2GetObj('b2ISL_Cont').scrollLeft = b2GetObj('b2ISL_Cont').scrollLeft + b2GetObj('b2List1').offsetWidth}
 b2GetObj('b2ISL_Cont').scrollLeft -= b2Space ;
}
function b2ISL_GoDown(){ //下翻
 clearInterval(b2MoveTimeObj);
 if(b2MoveLock) return;
 clearInterval(b2AutoPlayObj);
 b2MoveLock = true;
 b2ISL_ScrDown();
 b2MoveTimeObj = setInterval('b2ISL_ScrDown()',b2Speed);
}
function b2ISL_StopDown(){ //下翻停止
 clearInterval(b2MoveTimeObj);
 if(b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth - b2fill != 0 ){
  b2Comp = b2PageWidth - b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth + b2fill;
  b2CompScr();
 }else{
  b2MoveLock = false;
 }
 b2AutoPlay();
}
function b2ISL_ScrDown(){ //下翻动作
 if(b2GetObj('b2ISL_Cont').scrollLeft >= b2GetObj('b2List1').scrollWidth){b2GetObj('b2ISL_Cont').scrollLeft = b2GetObj('b2ISL_Cont').scrollLeft - b2GetObj('b2List1').scrollWidth;}
 b2GetObj('b2ISL_Cont').scrollLeft += b2Space ;
}
function b2CompScr(){
 var b2num;
 if(b2Comp == 0){b2MoveLock = false;return;}
 if(b2Comp < 0){ //上翻
  if(b2Comp < -b2Space){
   b2Comp += b2Space;
   b2num = b2Space;
  }else{
   b2num = -b2Comp;
   b2Comp = 0;
  }
  b2GetObj('b2ISL_Cont').scrollLeft -= b2num;
  setTimeout('b2CompScr()',b2Speed);
 }else{ //下翻
  if(b2Comp > b2Space){
   b2Comp -= b2Space;
   b2num = b2Space;
  }else{
   b2num = b2Comp;
   b2Comp = 0;
  }
  b2GetObj('b2ISL_Cont').scrollLeft += b2num;
  setTimeout('b2CompScr()',b2Speed);
 }
}
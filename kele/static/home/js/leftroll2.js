// JavaScript Document
var b2Speed = 50; //�ٶ�(����)
var b2Space = 40; //ÿ���ƶ�(px)
var b2PageWidth =198; //��ҳ���
var b2fill = 0; //������λ
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
function b2AutoPlay(){ //�Զ�����
 clearInterval(b2AutoPlayObj);
 b2AutoPlayObj = setInterval('b2ISL_GoDown();b2ISL_StopDown();',8000); //���ʱ��
}
function b2ISL_GoUp(){ //�Ϸ���ʼ
 if(b2MoveLock) return;
 clearInterval(b2AutoPlayObj);
 b2MoveLock = true;
 b2MoveTimeObj = setInterval('b2ISL_ScrUp();',b2Speed);
}
function b2ISL_StopUp(){ //�Ϸ�ֹͣ
 clearInterval(b2MoveTimeObj);
 if(b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth - b2fill != 0){
  b2Comp = b2fill - (b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth);
  b2CompScr();
 }else{
  b2MoveLock = false;
 }
 b2AutoPlay();
}
function b2ISL_ScrUp(){ //�Ϸ�����
 if(b2GetObj('b2ISL_Cont').scrollLeft <= 0){b2GetObj('b2ISL_Cont').scrollLeft = b2GetObj('b2ISL_Cont').scrollLeft + b2GetObj('b2List1').offsetWidth}
 b2GetObj('b2ISL_Cont').scrollLeft -= b2Space ;
}
function b2ISL_GoDown(){ //�·�
 clearInterval(b2MoveTimeObj);
 if(b2MoveLock) return;
 clearInterval(b2AutoPlayObj);
 b2MoveLock = true;
 b2ISL_ScrDown();
 b2MoveTimeObj = setInterval('b2ISL_ScrDown()',b2Speed);
}
function b2ISL_StopDown(){ //�·�ֹͣ
 clearInterval(b2MoveTimeObj);
 if(b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth - b2fill != 0 ){
  b2Comp = b2PageWidth - b2GetObj('b2ISL_Cont').scrollLeft % b2PageWidth + b2fill;
  b2CompScr();
 }else{
  b2MoveLock = false;
 }
 b2AutoPlay();
}
function b2ISL_ScrDown(){ //�·�����
 if(b2GetObj('b2ISL_Cont').scrollLeft >= b2GetObj('b2List1').scrollWidth){b2GetObj('b2ISL_Cont').scrollLeft = b2GetObj('b2ISL_Cont').scrollLeft - b2GetObj('b2List1').scrollWidth;}
 b2GetObj('b2ISL_Cont').scrollLeft += b2Space ;
}
function b2CompScr(){
 var b2num;
 if(b2Comp == 0){b2MoveLock = false;return;}
 if(b2Comp < 0){ //�Ϸ�
  if(b2Comp < -b2Space){
   b2Comp += b2Space;
   b2num = b2Space;
  }else{
   b2num = -b2Comp;
   b2Comp = 0;
  }
  b2GetObj('b2ISL_Cont').scrollLeft -= b2num;
  setTimeout('b2CompScr()',b2Speed);
 }else{ //�·�
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
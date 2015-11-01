(function(){
	/******************************************************************
	无阻加载JS	开始
	*******************************************************************/
	var getscript=function(s){
			var script
			,	head = document.head || document.getElementsByTagName( "head" )[0] || document.documentElement;
			return {
					send: function( ) {
						script = document.createElement( "script" );
						script.async = "async";
						script.charset = s.scriptCharset||'UTF-8';
						if(script.readyState){
							script.onreadystatechange = function() {
								if (/loaded|complete/.test(script.readyState)) {
									script = script.onreadystatechange = null;
									try{
										if ( head && script.parentNode ) {
											head.removeChild( script );
										}
									} catch (e) {}
									s.callback  && s.callback();
								}
							};
						}else{
							script.onload = function(){
								script.onload = null;
								s.callback  && s.callback();
							}; 
						}
						
						if(s.cache)
							s.url = s.url + ( /\?/.test( s.url ) ? "&" : "?" ) + "notcache=" + new Date().getTime();
						script.src = s.url;
						head.insertBefore( script, head.firstChild );
					},
					abort: function() {
						if ( script ) {
							if(script.readyState){
								script.onreadystatechange=null;
							}else{
								script.onload=null;
							}
						}
					}
				};
		}
	,	getjs=function(o){
			o=o||{};
			var stime=function(){
				st&&clearTimeout(st);
				o.callback && o.callback();
			},	scp=getscript({
				 url:o.url,
				 callback:stime,
				 scriptCharset:o.charset,
				 cache:o.cache
				 })
			,	st=setTimeout(scp.abort,(o.time||15*1000))
			;
			
			scp.send();
		
	};
	/******************************************************************
	无阻加载JS	结束
	*******************************************************************/
	var loadFn=function(fn,where){
		var cfn=function(){
				if(where()){
					clearInterval(t);
					fn&&fn();
				}
			}
		,	t = setInterval(cfn, 0)
		;
	};
	
	var show=function(cn,o){
		o=o||{};
		var d={};
		d['西安']={
			'img1':'http://xakj.913wen.com/images/qqq111.gif'//中间
		,	'img2':'http://xakj.913wen.com/images/qqq222.gif'//底部
		,	'img3':'http://xakj.913wen.com/images/qqq444.gif'//左边
		,	'img4':'http://xakj.913wen.com/images/qqq333.gif'//右边
		,	'webim':'http://dat.zoosnet.net/LR/Chatpre.aspx?id=DAT54459869&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		d['青海']={
			'img1':'http://xakj.913wen.com/images/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/qqq333.gif'
		,	'webim':'http://dat.zoosnet.net/LR/Chatpre.aspx?id=DAT54459869&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		d['宁夏']={
			'img1':'http://xakj.913wen.com/images/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/qqq333.gif'
		,	'webim':'http://dat.zoosnet.net/LR/Chatpre.aspx?id=DAT54459869&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		d['甘肃']={
			'img1':'http://xakj.913wen.com/images/lzdx/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/lzdx/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/lzdx/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/lzdx/qqq333.gif'
		,	'webim':'http://dgt.zoosnet.net/LR/Chatpre.aspx?id=DGT72063171&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		d['云南']={
			'img1':'http://xakj.913wen.com/images/kmdx/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/kmdx/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/kmdx/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/kmdx/qqq333.gif'
		,	'webim':'http://dat.zoosnet.net/LR/Chatpre.aspx?id=DAT50342982&lng=cn&e=lc-hmt-dx&r=lc-hmt-dx&p=lc-hmt-dx'
		};
		d['辽宁']={
			'img1':'http://xakj.913wen.com/images/sydx/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/sydx/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/sydx/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/sydx/qqq333.gif'
		,	'webim':'http://dlt.zoosnet.net/LR/Chatpre.aspx?id=DLT70400116&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		d['吉林']={
			'img1':'http://xakj.913wen.com/images/ccdx/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/ccdx/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/ccdx/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/ccdx/qqq333.gif'
		,	'webim':'http://dgt.zoosnet.net/LR/Chatpre.aspx?id=DGT93580919&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		d['黑龙江']={
			'img1':'http://xakj.913wen.com/images/heilj/qqq111.gif'
		,	'img2':'http://xakj.913wen.com/images/heilj/qqq222.gif'
		,	'img3':'http://xakj.913wen.com/images/heilj/qqq444.gif'
		,	'img4':'http://xakj.913wen.com/images/heilj/qqq333.gif'
		,	'webim':'http://dgt.zoosnet.net/LR/Chatpre.aspx?id=DGT82181361&lng=cn&e=lc-hmt&r=lc-hmt&p=lc-hmt'
		};
		var html=['<!--左弹窗-->',
		'<div id="<!--uuid-->_1" style="left: 2px; position: absolute; top: 50px; width: 165px; height: 440px;"> <a target="_blank"  href="<!--webim-->"> <img src="<!--img3-->" width="150" height="450"> </a> </div>',
		'<!--右弹窗-->',
		'<div id="<!--uuid-->_2" style="right: 2px; position: absolute; top: 50px; width: 160px; height: 369px;"> <a target="_blank"  href="<!--webim-->"> <img src="<!--img4-->" width="160" height="369"> </a> </div>',
		'<!--中间弹窗-->',
		'  <div id="<!--uuid-->_3" style="width: 495px; _width:650px; height:330px; _height:430px; overflow:hidden; position:relative; z-index: 900;">',
		'<div style="position:absolute; top:0px; _top:100px; left:0px; _left:140px; z-index:903;"> <a href="<!--webim-->" target="_blank"><img border="0" src="<!--img1-->"></a> </div>',
		'    <div style="position:absolute; top:0px; _top:100px; left:475px; _left:612px; z-index:904;"> <a name="close" href="javascript:;"><img src="http://www.dxjk120.com/images/closebtn.gif" border="0"></a> </div>',
		'  </div>',
		'<!--底部弹窗-->',
		'<div id="<!--uuid-->_4" style="position: fixed;_position: absolute;width: 100%;bottom: 0px;left: 0px;clip: rect(0 100% 100% 0);_top: expression(document.documentElement.scrollTop+document.documentElement.clientHeight-this.clientHeight);_left: expression(document.documentElement.scrollLeft + document.documentElement.clientWidth - offsetWidth);">',
		'  <div style="width: 900px;z-index: 899;height: 50px;overflow: hidden;background-repeat: no-repeat;margin: 0px auto;padding: 0px;"> <a target="_blank"  href="<!--webim-->"><img src="<!--img2-->" width="900" height="50" border="0"></a> </div>',
		'</div>',
		'<!--底部QQ弹窗-->',
		'<div id="<!--uuid-->_5" style="width:218px;">',
		'  <div style="width: 214px; height: 124px;"> <img src="http://xakj.913wen.com/images/qqq555.jpg" border="0" usemap="#winMap" id="win" style="position: relative; border: 0px; z-index: 100; top: 0px; left: 0px;">',
		'    <map name="winMap" id="winMap">',
		'      <a name="close" rea shape="rect" coords="177,3,215,21" href="javascript:;">',
		'      <a target="_blank" rea shape="rect" coords="4,31,210,115" href="<!--webim-->">',
		'    </map>',
		'  </div>',
		'</div>'].join("");
		var div=document.createElement('div')
		,	uuid=$G.uuidFast().replace(/-/ig,'')
		;
		html=$G.Config.wmReplace(html,'<!--uuid-->',uuid);
		$G.Each(function(i,k){
			html=$G.Config.wmReplace(html,'<!--'+k+'-->',this+'');
		},d[cn]);
		div.innerHTML=html;
		document.body.appendChild(div);
		
		var int1={
				position:'fixed',
				isTop:true,
				isLeft:true
			}
		,	int2={
				position:'fixed',
				isTop:true,
				isRight:true
			}
		,	int3={
				position:'fixed',
				isCenter:true
			}
		,	int4={
				position:'fixed',
				isBottom:true,
				isLeft:true
			}
		,	int5={
				position:'fixed',
				isBottom:true,
				isRight:true
			}
		;
		
		var domDiv1=$G('#'+uuid+'_1',div);
		domDiv1.WinAlt(int1);
		$G('.close',domDiv1[0]).click(function(){
			domDiv1.display().none();
		});
		var domDiv2=$G('#'+uuid+'_2',div);
		domDiv2.WinAlt(int2);
		$G('.close',domDiv2[0]).click(function(){
			domDiv2.display().none();
		});
		var domDiv3=$G('#'+uuid+'_3',div);
		domDiv3.WinAlt(int3);
		$G('.close',domDiv3[0]).click(function(){
			domDiv3.display().none();
		});
		var domDiv4=$G('#'+uuid+'_4',div);
		domDiv4.WinAlt(int4);
		$G('.close',domDiv4[0]).click(function(){
			domDiv4.display().none();
		});
		var domDiv5=$G('#'+uuid+'_5',div);
		domDiv5.WinAlt(int5);
		$G('.close',domDiv5[0]).click(function(){
			domDiv5.display().none();
		});
		var eff=new $G.eff(domDiv5);
		eff.shake();
	};
	/******************************************************************
	
	*******************************************************************/
	//执行无限加载公共类 必须在回调方法之下，请注意执行顺序
	getjs({
		url:'http://static.adx.haomeit.com/js/Glacier.js'
	,	callback:function(){
			$G.getjs({url:'http://pv.sohu.com/cityjson?ie=utf-8'});
				loadFn(function(){
					try{
						var cname=returnCitySN['cname']||'';
						if(cname){
							if(cname.indexOf("西安")>-1){
								show('西安');
							}else if(cname.indexOf("青海")>-1){
								show('青海');
							}else if(cname.indexOf("宁夏")>-1){
								show('宁夏');
							}else if(cname.indexOf("甘肃")>-1){
								show('甘肃');
							}else if(cname.indexOf("云南")>-1){
								show('云南');
							}else if(cname.indexOf("辽宁")>-1){
								show('辽宁');
							}else if(cname.indexOf("吉林")>-1){
								show('吉林');
							}else if(cname.indexOf("黑龙江")>-1){
								show('黑龙江');
							}else{
								//show('西安');
							}
						}
					}catch(e){
					}
					
				},function(){
					if((typeof returnCitySN)=='object'){
						return true;
					}else
						return false;
				});
		}
	});
})();

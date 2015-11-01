//数据可以动态生成，格式自己定义，cha对应china-zh.js中省份的简称
var dataStatus = [{ cha: 'HKG', name: '香港',v:34, des: '' },
				 { cha: 'HAI', name: '海南',v:32, des: '' },
				 { cha: 'YUN', name: '云南',v:17, des: '' },
				 { cha: 'BEJ', name: '北京',v:1, des: '' ,count:97},
				 { cha: 'TAJ', name: '天津',v:3, des: '',count:17 },
				 { cha: 'XIN', name: '新疆',v:13, des: ''},
				 { cha: 'TIB', name: '西藏',v:14, des: '' },
				 { cha: 'QIH', name: '青海',v:12, des: '' },
				 { cha: 'GAN', name: '甘肃',v:11, des: '' },
				 { cha: 'NMG', name: '内蒙古',v:33, des: '' },
				 { cha: 'NXA', name: '宁夏',v:10, des: '' },
				 { cha: 'SHX', name: '山西',v:8, des: '',count:58 },
				 { cha: 'LIA', name: '辽宁',v:5, des: '' },
				 { cha: 'JIL', name: '吉林',v:6, des: '' },
				 { cha: 'HLJ', name: '黑龙江',v:7, des: '' },
				 { cha: 'HEB', name: '河北',v:16, des: '' },
				 { cha: 'SHD', name: '山东',v:21, des: '' },
				 { cha: 'HEN', name: '河南',v:20, des: '' },
				 { cha: 'SHA', name: '陕西',v:9, des: '',count:25 },
				 { cha: 'SCH', name: '四川',v:15, des: '' },
				 { cha: 'CHQ', name: '重庆',v:4, des: '' },
				 { cha: 'HUB', name: '湖北',v:19, des: '',count:13 },
				 { cha: 'ANH', name: '安徽',v:23, des: '' },
				 { cha: 'JSU', name: '江苏',v:22, des: '',count:99 },
				 { cha: 'SHH', name: '上海',v:2, des: '' ,count:101},
				 { cha: 'ZHJ', name: '浙江',v:24, des: '' ,count:66},
				 { cha: 'FUJ', name: '福建',v:27, des: '',count:30 },
				 { cha: 'TAI', name: '台湾',v:28, des: '' },
				 { cha: 'JXI', name: '江西',v:25, des: '' },
				 { cha: 'HUN', name: '湖南',v:30, des: '',count:18 },
				 { cha: 'GUI', name: '贵州',v:18, des: '' },
				 { cha: 'GXI', name: '广西',v:31, des: '' }, 
				 { cha: 'GUD', name: '广东',v:29, des: '',count:20}];
	$('#container').vectorMap({ map: 'china_zh',
		color: "#99CCFF", //地图颜色
backgroundColor:"",
		onLabelShow: function (event, label, code) {//动态显示内容
			$.each(dataStatus, function (i, items) {
				if (code == items.cha) {
					label.html(items.name);
				}
			});
		},onRegionClick:function(event, code){
	loaddqyy(code);
}
	});
	
function toHex(d){
    if(isNaN(d)){
     d=0; 
 }
 //16进制转换方法
 var n=new Number(d).toString(16);
 return (n.length==1?"0"+n:n);
}
$.each(dataStatus, function (i, items) {
	if (items.count!=null) {//动态设定颜色，此处用了自定义简单的判断
		var red=10+parseInt(items.count/4),green=items.count,blue=255;
		var josnStr = "{" + items.cha + ":'"+"#"+toHex(red)+toHex(green)+toHex(blue)+"'}";
		$('#container').vectorMap('set', 'colors', eval('(' + josnStr + ')'));
	}
});
            $('.jvectormap-zoomin').click(); //放大
				var loaddqyy=function(code){
				$.each(dataStatus, function (i, items) {
                        if (code == items.cha) {
							$(".zh-2ld span a").html(items.name);
							$.post("Hospital/list",{provinceId:items.v},function(data){$(".zh-2ld ul").html(data);});
                        }
                    });
};
for(var i=0;i<dataStatus.length;i++){
if(dataStatus[i].name==remote_ip_info.province){
loaddqyy(dataStatus[i].cha);
}
}
function lowerMove1(id,index,count,link)
{
var defaultCss;
	var SelectCss;
	if(id==1 ){defaultCss="lis1";SelectCss="lis1_a";}
	if(id==10 ){defaultCss="lis10";SelectCss="lis10_a";}
	if(id==11 ){defaultCss="lis11";SelectCss="lis11_a";}
	if(id==12 ){defaultCss="lis12";SelectCss="lis12_a";}
	if(id==13 ){defaultCss="lis13";SelectCss="lis13_a";}
 for(var n=1;n<=count;n++)
 {
  document.getElementById("Button1"+id+n).className=defaultCss;
 }
 document.getElementById("Button1"+id+index).className=SelectCss;
}
function lowerMove2(id,index,count,link)
{
 for(var n=1;n<=count;n++)
 {
  document.getElementById("ContentBox2"+id+n).style.display='none';
 }
 document.getElementById("ContentBox2"+id+index).style.display='block';
}






function lowerMoves(id,index,count,link)
{
	var defaultCss;
	var SelectCss;
	if(id==2 ){defaultCss="lis2";SelectCss="lis2_a";}
	if(id==3){defaultCss="lis3";SelectCss="lis3_a";}
if(id==4){defaultCss="lis4";SelectCss="lis4_a";}
if(id==5){defaultCss="lis5";SelectCss="lis5_a";}
if(id==6){defaultCss="lis6";SelectCss="lis6_a";}
if(id==7){defaultCss="lis7";SelectCss="lis7_a";}
if(id==8){defaultCss="lis8";SelectCss="lis8_a";}
if(id==8){defaultCss="lis8";SelectCss="lis8_a";}
if(id==9){defaultCss="lis9";SelectCss="lis9_a";}
if(id==14){defaultCss="lis14";SelectCss="lis14_a";}
if(id==15){defaultCss="lis15";SelectCss="lis15_a";}
 for(var n=1;n<=count;n++)
 {
  document.getElementById("Buttons"+id+n).className=defaultCss;

  document.getElementById("ContentBoxsa"+id+n).style.display='none';
 }
 document.getElementById("Buttons"+id+index).className=SelectCss;

 document.getElementById("ContentBoxsa"+id+index).style.display='block';
}


function lowerMovea(id,index,count,link)
{
	var defaultCss;
	var SelectCss;
	if(id==20 ){defaultCss="lis20";SelectCss="lis20_a";}
if(id==21 ){defaultCss="lis21";SelectCss="lis21_a";}
if(id==22 ){defaultCss="lis22";SelectCss="lis22_a";}
if(id==23 ){defaultCss="lis23";SelectCss="lis23_a";}
if(id==24 ){defaultCss="lis24";SelectCss="lis24_a";}
if(id==25 ){defaultCss="lis25";SelectCss="lis25_a";}
 for(var n=1;n<=count;n++)
 {
  document.getElementById("Buttons"+id+n).className=defaultCss;
    document.getElementById("ContentBoxs"+id+n).style.display='none';
  document.getElementById("ContentBoxsa"+id+n).style.display='none';
 }
 document.getElementById("Buttons"+id+index).className=SelectCss;
  document.getElementById("ContentBoxs"+id+index).style.display='block';
 document.getElementById("ContentBoxsa"+id+index).style.display='block';
}
function Marqueelower()
{
	this.ID = document.getElementById(arguments[0]);
	if(!this.ID)
	{
		alert("?????\"" + arguments[0] + "\"?????\r\n?????ID??????!");
		this.ID = -1;
		return;
	}
	this.Direction = this.Width = this.Height = this.DelayTime = this.WaitTime = this.CTL = this.StartID = this.Stop = this.MouseOver = 0;
	this.Step = 1;
	this.Timer = 30;
	this.DirectionArray = {"top":0 , "up":0 , "bottom":1 , "down":1 , "left":2 , "right":3};
	if(typeof arguments[1] == "number" || typeof arguments[1] == "string")this.Direction = arguments[1];
	if(typeof arguments[2] == "number")this.Step = arguments[2];
	if(typeof arguments[3] == "number")this.Width = arguments[3];
	if(typeof arguments[4] == "number")this.Height = arguments[4];
	if(typeof arguments[5] == "number")this.Timer = arguments[5];
	if(typeof arguments[6] == "number")this.DelayTime = arguments[6];
	if(typeof arguments[7] == "number")this.WaitTime = arguments[7];
	if(typeof arguments[8] == "number")this.ScrollStep = arguments[8];
	this.ID.style.overflow = this.ID.style.overflowX = this.ID.style.overflowY = "hidden";
	this.ID.noWrap = true;
	this.IsNotOpera = (navigator.userAgent.toLowerCase().indexOf("opera") == -1);
	if(arguments.length >= 7)this.Start();
}

Marqueelower.prototype.Start = function()
{
	if(this.ID == -1)return;
	if(this.WaitTime < 800)this.WaitTime = 800;
	if(this.Timer < 20)this.Timer = 20;
	if(this.Width == 0)this.Width = parseInt(this.ID.style.width);
	if(this.Height == 0)this.Height = parseInt(this.ID.style.height);
	if(typeof this.Direction == "string")this.Direction = this.DirectionArray[this.Direction.toString().toLowerCase()];
	this.HalfWidth = Math.round(this.Width / 2);
	this.HalfHeight = Math.round(this.Height / 2);
	this.BakStep = this.Step;
	this.ID.style.width = this.Width + "px";
	this.ID.style.height = this.Height + "px";
	if(typeof this.ScrollStep != "number")this.ScrollStep = this.Direction > 1 ? this.Width : this.Height;
	var templateLeft = "<table cellspacing='0' cellpadding='0' style='border-collapse:collapse;display:inline;'><tr><td noWrap=true style='white-space: nowrap;word-break:keep-all;'>MSCLASS_TEMP_HTML</td><td noWrap=true style='white-space: nowrap;word-break:keep-all;'>MSCLASS_TEMP_HTML</td></tr></table>";
	var templateTop = "<table cellspacing='0' cellpadding='0' style='border-collapse:collapse;'><tr><td>MSCLASS_TEMP_HTML</td></tr><tr><td>MSCLASS_TEMP_HTML</td></tr></table>";
	var msobj = this;
	msobj.tempHTML = msobj.ID.innerHTML;
	if(msobj.Direction <= 1)
	{
		msobj.ID.innerHTML = templateTop.replace(/MSCLASS_TEMP_HTML/g,msobj.ID.innerHTML);
	}
	else
	{
		if(msobj.ScrollStep == 0 && msobj.DelayTime == 0)
		{
			msobj.ID.innerHTML += msobj.ID.innerHTML;
		}
		else
		{
			msobj.ID.innerHTML = templateLeft.replace(/MSCLASS_TEMP_HTML/g,msobj.ID.innerHTML);
		}
	}
	var timer = this.Timer;
	var delaytime = this.DelayTime;
	var waittime = this.WaitTime;
	msobj.StartID = function(){msobj.Scroll()}
	msobj.Continue = function()
				{
					if(msobj.MouseOver == 1)
					{
						setTimeout(msobj.Continue,delaytime);
					}
					else
					{	clearInterval(msobj.TimerID);
						msobj.CTL = msobj.Stop = 0;
						msobj.TimerID = setInterval(msobj.StartID,timer);
					}
				}

	msobj.Pause = function()
			{
				msobj.Stop = 1;
				clearInterval(msobj.TimerID);
				setTimeout(msobj.Continue,delaytime);
			}

	msobj.Begin = function()
		{
			msobj.ClientScroll = msobj.Direction > 1 ? msobj.ID.scrollWidth / 2 : msobj.ID.scrollHeight / 2;
			if((msobj.Direction <= 1 && msobj.ClientScroll <= msobj.Height + msobj.Step) || (msobj.Direction > 1 && msobj.ClientScroll <= msobj.Width + msobj.Step))			{
				msobj.ID.innerHTML = msobj.tempHTML;
				delete(msobj.tempHTML);
				return;
			}
			delete(msobj.tempHTML);
			msobj.TimerID = setInterval(msobj.StartID,timer);
			if(msobj.ScrollStep < 0)return;
			msobj.ID.onmousemove = function(event)
						{
							if(msobj.ScrollStep == 0 && msobj.Direction > 1)
							{
								var event = event || window.event;
								if(window.event)
								{
									if(msobj.IsNotOpera)
									{
										msobj.EventLeft = event.srcElement.id == msobj.ID.id ? event.offsetX - msobj.ID.scrollLeft : event.srcElement.offsetLeft - msobj.ID.scrollLeft + event.offsetX;
									}
									else
									{
										msobj.ScrollStep = null;
										return;
									}
								}
								else
								{
									msobj.EventLeft = event.layerX - msobj.ID.scrollLeft;
								}
								msobj.Direction = msobj.EventLeft > msobj.HalfWidth ? 3 : 2;
								msobj.AbsCenter = Math.abs(msobj.HalfWidth - msobj.EventLeft);
								msobj.Step = Math.round(msobj.AbsCenter * (msobj.BakStep*2) / msobj.HalfWidth);
							}
						}
			msobj.ID.onmouseover = function()
						{
							if(msobj.ScrollStep == 0)return;
							msobj.MouseOver = 1;
							clearInterval(msobj.TimerID);
						}
			msobj.ID.onmouseout = function()
						{
							if(msobj.ScrollStep == 0)
							{
								if(msobj.Step == 0)msobj.Step = 1;
								return;
							}
							msobj.MouseOver = 0;
							if(msobj.Stop == 0)
							{
								clearInterval(msobj.TimerID);
								msobj.TimerID = setInterval(msobj.StartID,timer);
							}
						}
		}
	setTimeout(msobj.Begin,waittime);
}

Marqueelower.prototype.Scroll = function()
{
	switch(this.Direction)
	{
		case 0:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0)
			{
				this.ID.scrollTop += this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else
			{
				if(this.ID.scrollTop >= this.ClientScroll)
				{
					this.ID.scrollTop -= this.ClientScroll;
				}
				this.ID.scrollTop += this.Step;
			}
		break;

		case 1:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0)
			{
				this.ID.scrollTop -= this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else
			{
				if(this.ID.scrollTop <= 0)
				{
					this.ID.scrollTop += this.ClientScroll;
				}
				this.ID.scrollTop -= this.Step;
			}
		break;

		case 2:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0)
			{
				this.ID.scrollLeft += this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else
			{
				if(this.ID.scrollLeft >= this.ClientScroll)
				{
					this.ID.scrollLeft -= this.ClientScroll;
				}
				this.ID.scrollLeft += this.Step;
			}
		break;

		case 3:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0)
			{
				this.ID.scrollLeft -= this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else
			{
				if(this.ID.scrollLeft <= 0)
				{
					this.ID.scrollLeft += this.ClientScroll;
				}
				this.ID.scrollLeft -= this.Step;
			}
		break;
	}
}
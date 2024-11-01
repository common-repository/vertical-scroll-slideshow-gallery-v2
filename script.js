/**
 *     Vertical scroll slideshow gallery v2
 *     Copyright (C) 2011 - 2022 www.gopiplus.com
 *     http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
	
var ie=document.all
var dom=document.getElementById

if (vs2_slideimages.length>2)
vs2_i=2
else
vs2_i=0

function vs2_move1(whichlayer){
tlayer=eval(whichlayer)
if (tlayer.top>0&&tlayer.top<=5){
tlayer.top=0
setTimeout("vs2_move1(tlayer)",vs2_pausebetweenimages)
setTimeout("vs2_move2(document.vs2_main.document.vs2_second)",vs2_pausebetweenimages)
return
}
if (tlayer.top>=tlayer.document.height*-1){
tlayer.top-=5
setTimeout("vs2_move1(tlayer)",50)
}
else{
tlayer.top=parseInt(vs2_scrollerheight)
tlayer.document.write(vs2_slideimages[vs2_i])
tlayer.document.close()
if (vs2_i==vs2_slideimages.length-1)
vs2_i=0
else
vs2_i++
}
}

function vs2_move2(whichlayer){
tlayer2=eval(whichlayer)
if (tlayer2.top>0&&tlayer2.top<=5){
tlayer2.top=0
setTimeout("vs2_move2(tlayer2)",vs2_pausebetweenimages)
setTimeout("vs2_move1(document.vs2_main.document.vs2_first)",vs2_pausebetweenimages)
return
}
if (tlayer2.top>=tlayer2.document.height*-1){
tlayer2.top-=5
setTimeout("vs2_move2(tlayer2)",50)
}
else{
tlayer2.top=parseInt(vs2_scrollerheight)
tlayer2.document.write(vs2_slideimages[vs2_i])
tlayer2.document.close()
if (vs2_i==vs2_slideimages.length-1)
vs2_i=0
else
vs2_i++
}
}

function vs2_move3(whichdiv){
tdiv=eval(whichdiv)
if (parseInt(tdiv.style.top)>0&&parseInt(tdiv.style.top)<=5){
tdiv.style.top=0+"px"
setTimeout("vs2_move3(tdiv)",vs2_pausebetweenimages)
setTimeout("vs2_move4(vs2_second2_obj)",vs2_pausebetweenimages)
return
}
if (parseInt(tdiv.style.top)>=tdiv.offsetHeight*-1){
tdiv.style.top=parseInt(tdiv.style.top)-5+"px"
setTimeout("vs2_move3(tdiv)",50)
}
else{
tdiv.style.top=vs2_scrollerheight
tdiv.innerHTML=vs2_slideimages[vs2_i]
if (vs2_i==vs2_slideimages.length-1)
vs2_i=0
else
vs2_i++
}
}

function vs2_move4(whichdiv){
tdiv2=eval(whichdiv)
if (parseInt(tdiv2.style.top)>0&&parseInt(tdiv2.style.top)<=5){
tdiv2.style.top=0+"px"
setTimeout("vs2_move4(tdiv2)",vs2_pausebetweenimages)
setTimeout("vs2_move3(vs2_first2_obj)",vs2_pausebetweenimages)
return
}
if (parseInt(tdiv2.style.top)>=tdiv2.offsetHeight*-1){
tdiv2.style.top=parseInt(tdiv2.style.top)-5+"px"
setTimeout("vs2_move4(vs2_second2_obj)",50)
}
else{
tdiv2.style.top=vs2_scrollerheight
tdiv2.innerHTML=vs2_slideimages[vs2_i]
if (vs2_i==vs2_slideimages.length-1)
vs2_i=0
else
vs2_i++
}
}

function startscroll(){
if (ie||dom){
vs2_first2_obj=ie? vs2_first2 : document.getElementById("vs2_first2")
vs2_second2_obj=ie? vs2_second2 : document.getElementById("vs2_second2")
vs2_move3(vs2_first2_obj)
vs2_second2_obj.style.top=vs2_scrollerheight
vs2_second2_obj.style.visibility='visible'
}
else if (document.layers){
document.vs2_main.visibility='show'
vs2_move1(document.vs2_main.document.vs2_first)
document.vs2_main.document.vs2_second.top=parseInt(vs2_scrollerheight)+5
document.vs2_main.document.vs2_second.visibility='show'
}
}

window.onload=startscroll

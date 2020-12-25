d=document;w=window;c=console;


w.onload=()=>{
  // LAZY LOAD FUNCTIONS MODULE
  var lBs=[].slice.call(d.querySelectorAll(".lazy-background")),lIs=[].slice.call(d.querySelectorAll(".lazy")),opt={threshold:.01};
  if("IntersectionObserver" in window){
    let lBO=new IntersectionObserver(es=>{es.forEach(e=>{if(e.isIntersecting){let l=e.target;l.classList.add("visible");lBO.unobserve(l)}})},opt),
        lIO=new IntersectionObserver(es=>{es.forEach(e=>{if(e.isIntersecting){let l=e.target;l.classList.remove("lazy");lIO.unobserve(l);l.srcset=l.dataset.url}})},opt);
    lIs.forEach(lI=>{lIO.observe(lI)});lBs.forEach(lB=>{lBO.observe(lB)});
  }

  // Modules setup
	growUpController.setup()
	obseController.setup()
  d.getElementById("load").style.top="-100vh";
}


async function ajax2(formData, url = lt_data.ajaxurl) {
	try{
		let response = await fetch(url, { method: 'POST', body: formData, });
		return await response.json();
	} catch ( err ) { console.error(err); }
}

async function ajax3(formData, url = lt_data.ajaxurl) {
	try{
		let response = await fetch(url, { method: 'POST', body: formData, });
		return await response.text();
	} catch ( err ) { console.error(err); }
}








/*
=altClassFromSelector

alternates a class from a selector of choice, for example:
<div class="someButton" onclick="altClassFromSelector('activ', '#navBar')"></div>
*/
const altClassFromSelector = ( clase, selector, dont_remove = false )=>{
  const x = d.querySelector(selector);
  // if there is a main class removes all other classes
  if(dont_remove){
    x.classList.forEach( item =>{
      if( dont_remove.findIndex( element => element == item) == -1 && item!=clase ){
        x.classList.remove(item);
      }
    });
  }

  if(x.classList.contains(clase)){
		if(dont_remove){
			if( dont_remove.findIndex( element => element == clase) == -1 ){
				x.classList.remove(clase)
			}
		} else {
			x.classList.remove(clase)
		}
  }else{
		if(clase){
			x.classList.add(clase)
		}
  }
}











// GO BACK BUTTONS
function goBack(){w.history.back()}














//Accordion //Desplegable
var acc = d.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click",()=>{
    this.classList.toggle("active");
    // TODO: Hacer que se puede elegir el elemento a acordionar
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
      panel.style.padding = "0";
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
      panel.style.padding = "20px";
    }
  });
}

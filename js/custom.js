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
  const elementos = [...d.querySelectorAll(selector)];
  elementos.forEach(elemento => {
    // const x = d.querySelector(selector);
    // if there is a main class removes all other classes
    if(dont_remove){
      elemento.classList.forEach( item =>{
        if( dont_remove.findIndex( element => element == item) == -1 && item!=clase ){
          elemento.classList.remove(item);
        }
      });
    }

    if(elemento.classList.contains(clase)){
      if(dont_remove){
        if( dont_remove.findIndex( element => element == clase) == -1 ){
          elemento.classList.remove(clase)
        }
      } else {
        elemento.classList.remove(clase)
      }
    }else{
      if(clase){
        elemento.classList.add(clase)
      }
    }
  })
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










const activate_paginators = (paginators)=>{
  paginators.forEach((item, i) => {
    item.onclick = (self)=>{
      let page = self.target.dataset.pagination;
      let cycle_container = self.target.parentElement.parentElement;
      fil_pa_sea(cycle_container, false, page, false)
    }
  });
}
const activate_filters = (filters)=>{
  filters.forEach((item, i) => {
    // c.log(item)
    let element = item;
    let keep_going = true;
    let cycle_name = '';
    let cycle_container;
    i=0;

    var button = item,
        parent = button.querySelector('input').dataset.parent,
        category = button.querySelector('input').dataset.slug,
        taxonomy = button.querySelector('input').dataset.taxonomy;

    while(keep_going){
      cycle_name = element.dataset.cycleContainer;
      if (cycle_name){
        cycle_container = d.querySelector('[data-cycle='+cycle_name+']')
        keep_going = false;
      } else if (element == d.querySelector('body')) {
        c.log(item)
        c.log('has configurado mal el filtro, debes poner \'data-cycle-container="name"\'')
        keep_going = false;
      } else {
        element = element.parentElement;
      }
      i++;
    }
    item.onchange = (self)=>{
      let filter = {
        taxonomy: taxonomy,
        parent: parent,
        terms: category,
      }
      fil_pa_sea(cycle_container, filter, false, false);
    }
  });
}

const activate_searchers = (searchers) =>{
  searchers.forEach( searcher => {
    let element = searcher;
    let cycle_container;
    let keep_going = true;


    // busca el nombre del cyclo escrito en algun padre
    while(keep_going){
      cycle_name = element.dataset.cycleContainer;
      if (cycle_name){
        cycle_container = d.querySelector('[data-cycle='+cycle_name+']')
        keep_going = false;
      } else if (element == d.querySelector('body')) {
        c.log('has configurado mal el filtro, debes poner \'data-cycle-container="name"\'')
        keep_going = false;
      } else {
        element = element.parentElement;
      }
      i++;
    }

    // c.log(cycle_container);

    var searchTimeOut;
    searcher.oninput = ()=>{
      clearTimeout(searchTimeOut);
      searchTimeOut = setTimeout(()=>{
        fil_pa_sea(cycle_container, false, false, searcher.value);
      }, 1200);
    }
  });
}

// This part activates the filters on change
filterers = [...document.querySelectorAll('[data-cycle-container] .selectBoxOption')]
if (filterers){ activate_filters(filterers) }

// This part activates the pagination on click
paginators = [...document.querySelectorAll('.pagination_link')]
if (paginators){ activate_paginators(paginators) }

// This part activates the search on write
searchers = [...document.querySelectorAll('.Searcher')]
if (searchers){ activate_searchers(searchers) }








//
// shareButton = d.querySelector('.log_title');
// shareButton.addEventListener('click', async () => {
//   try {
//     await navigator.share({ title: "Example Page", url: "" });
//     console.log("Data was shared successfully");
//   } catch (err) {
//     alert(err.message)
//     // console.error("Share failed:", err.message);
//   }
// });








// console.log(JSON.stringify({page:2,tax:[{taxonomy:'category',parent:'categorias',terms:'test'},]}))
// let test = {
//   tax:{
//     categorias:{taxonomy:'category',parent:'categorias',category:'test'},
//     otro:{pepe:'pepe',},
//   }
// }
// console.log('test',JSON.stringify(test))

// fil_pa_sea stands for "filter pagination search"
const fil_pa_sea = (cycle_container, filter, page, keyword)=>{
  let card  = cycle_container.dataset.card;
  let cycle = cycle_container.dataset.cycle ? cycle_container.dataset.cycle : 'filters';
  // c.log('saved query: ', window[cycle].query);
  let query = JSON.parse(window[cycle].query);
  query.category_name = '';
  query.cat = '';
  // console.log(query)


  // URL HANDLING
  let search = location.search.substring(1),
  params = new URLSearchParams(search),
  url_no_params = w.location.href.split('?')[0],
  url_params = '',
  args = params.get(cycle) ? JSON.parse(params.get(cycle)) : {};
  // c.log('args: ', args)


  // filters part
  // modelo: filters={page:2,tax:[{taxonomy:'category',parent:'categorias',terms:'test'},]}
  query.tax_query = {}
  query.tax_query['relation'] = 'AND';
  // TODO: que pasa si hay varios filtros en un mismo ciclo????
  if(!args.tax) args.tax = {}
  if(filter){
    if( filter.terms == '0' ){
      delete args.tax[filter.parent];
      // delete query.tax_query[filter.parent];
    } else {
      args.tax[filter.parent] = {
        terms:   filter.terms,
        taxonomy:filter.taxonomy,
        parent:  filter.parent,
      }
    }
  }
  if( !Object.keys(args.tax).length ) delete args.tax;
  // end of filters part

  // page part
  if ( !page ) page = query.paged ? query.paged : 1;
  let current = args.page ? args.page : 1;
  if ( page == 'next' ) { page = current + 1; }
  if ( page == 'prev' ) { page = current - 1; }
  args.page = page;
  if (page == 1) delete args.page;
  // end of page part


  // search part
  // c.log('keyword: ', keyword);
  if (keyword) args.search = keyword;
  if (keyword === '') delete args.search;
  if ( !args.search ) delete args.search;
  // end of search part

  if( !!Object.keys(args).length ){
    params.set(cycle, JSON.stringify(args))
    url_params = '?' + params.toString();
  } else {
    url_params = '';
  }
  w.history.replaceState('', 'Title', url_no_params + url_params);
  // END OF URL HANDLING


  // QUERY HANDLING
  query.paged = page;

  for (var item in args.tax) {
    if(args.tax[item].parent && args.tax[item].taxonomy && args.tax[item].terms){
      query.tax_query[args.tax[item].parent] = {
        'taxonomy' : args.tax[item].taxonomy,
        'field'    : 'slug',
        'terms'    : args.tax[item].terms,
      }
    }
  }

  query.s = keyword;

  query = JSON.stringify(query)
  window[cycle].query = query
  // c.log('query: ', query)
  // END OF QUERY HANDLING



  var formData = new FormData();
  formData.append( 'action', 'lt_pagination_2' );
  formData.append( 'query', query );
  // formData.append( 'page', page );
  formData.append( 'card', card );
  ajax3(formData).then( respuesta => {
    // console.log(respuesta)
    cycle_container.innerHTML = respuesta;

    // This part activates the pagination on click
    paginators = [...document.querySelectorAll('.pagination_link')]
    if (paginators){ activate_paginators(paginators); }
  });
}

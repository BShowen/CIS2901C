window.onload = ()=>{
  const show_form_button = document.querySelector('.show_form');
  const form_container = document.querySelector('.new_customer_form');
  show_form_button.addEventListener('click', (e)=>{
    toggleForm(show_form_button, form_container);
  })
}

function toggleForm(button, container){
  if(button.classList.contains('collapsed')){
    container.style.borderLeft = '1px solid black';  
    container.style.borderRight = '1px solid black';  
    container.style.borderBottom = '1px solid black';  
    container.style.paddingTop = '10px';
    container.style.height = '150px';

    button.innerText = "Cancel";
    button.className = 'show_form expanded';
  }else{
    container.style.borderLeft = 'none';  
    container.style.borderRight = 'none';  
    container.style.borderBottom = 'none';  
    container.style.paddingTop = '0px';
    container.style.height = '0px';

    button.innerText = "New Customer";
    button.className = 'show_form collapsed';
  }
}

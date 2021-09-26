window.onload = ()=>{
  const show_form_button = document.querySelector('.show_form');
  const form_container = document.querySelector('.form_container');
  const styles = {
    padding: getStyle(form_container, 'padding'), 
    height: getStyle(form_container, 'height'), 
    border: getStyle(form_container, 'border'),
    buttonText: show_form_button.innerText,
  };

  form_container.style.height = '0px';
  form_container.style.padding = '0px';
  form_container.style.border = 'none';

  show_form_button.addEventListener('click', (e)=>{
    toggleForm(show_form_button, form_container, styles);
  })
}

function toggleForm(button, container, styles = {}){
  const is_collapsed = button.classList.contains('collapsed');
  
  if(is_collapsed){
    container.style.height = styles.height;
    container.style.border = styles.border;
    container.style.padding = styles.padding;
    button.innerText = 'Cancel';
    button.className = 'show_form expanded';
  }else{
    container.style.height = '0px';
    button.innerText = styles.buttonText;
    button.className = 'show_form collapsed';
    setTimeout(()=>{
      container.style.padding = '0px';
    }, 450)
    setTimeout(()=>{
      container.style.border = 'none';
    }, 900);
  }

}

function getStyle(element, style){
  const styles = getComputedStyle(element);
  return styles[style];
}
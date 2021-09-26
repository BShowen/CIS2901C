window.onload = ()=>{
  formAnimation();
  tableAnimation();
}

function formAnimation(){
  const showFormButton = document.querySelector('.show_form');
  const formContainer = document.querySelector('.form_container');
  if(showFormButton && formContainer){
    const styles = {
      padding: getStyle(formContainer, 'padding'), 
      height: getStyle(formContainer, 'height'), 
      border: getStyle(formContainer, 'border'),
      buttonText: showFormButton.innerText,
    };
  
    formContainer.style.height = '0px';
    formContainer.style.padding = '0px';
    formContainer.style.border = 'none';
  
    showFormButton.addEventListener('click', (e)=>{
      toggleForm(showFormButton, formContainer, styles);
    });
  }  
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

function tableAnimation(){
  const tableRows = document.getElementsByTagName('tr');
  if(tableRows.length > 0){
    const lastRow = tableRows[tableRows.length - 1];
    lastRow.style.backgroundColor = 'white';
  }
}
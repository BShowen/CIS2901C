window.onload = ()=>{
  formAnimation();
  tableAnimation();
}

function formAnimation(){
  const showFormButton = document.querySelector('.show_form');
  const formContainer = document.querySelector('.form_container');
  if(showFormButton && formContainer){
    const styles = {
      height: getStyle(formContainer, 'height'), 
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
    button.innerText = 'Cancel';
    button.className = 'show_form expanded';
  }else{
    container.style.height = '0px';
    button.innerText = styles.buttonText;
    button.className = 'show_form collapsed';
  }

}

function getStyle(element, style){
  const styles = getComputedStyle(element);
  return styles[style];
}

function tableAnimation(){
  const newRow = document.querySelector('.new_row');
  if(newRow){
    newRow.style.backgroundColor = "white";
    setTimeout(()=>{
      newRow.classList = null;
      newRow.style.backgroundColor = null;
    }, 2000)
  }
}
window.onload = ()=>{
  formAnimation();
  tableAnimation();
  clickableTableRows();
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
    newRow.style.backgroundColor = "#F4F7FA";
    setTimeout(()=>{
      newRow.classList = null;
      newRow.style.backgroundColor = null;
    }, 2000)
  }
}

// This function makes table rows clickable. When a row is click the user is redirected to the page that shows
// more detailed data for the row that was clicked. 
function clickableTableRows(){
  // Get all of the table rows that are clickable. 
  const clickableRows = document.querySelectorAll('.clickable');
  // Add an event listener to each row. 
  clickableRows.forEach((row)=>{
    row.addEventListener('click', (event)=>{
      const host = window.location.origin; //get the host name. For example "https://localHost:8080"
      const newPathName = row.dataset.href //get the redirect link. 
      window.location.href = host + newPathName; //redirect the user. 
    });
  });
}
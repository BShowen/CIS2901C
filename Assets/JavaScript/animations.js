window.onload = ()=>{
  formAnimation();
  tableAnimation();
  clickableTableRows();
  editCustomerDetailsBUtton();
  cancelCustomerEditForm();
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
      const newPathName = row.dataset.href; //get the redirect link. 
      window.location.href = host + newPathName; //redirect the user. 
    });
  });
}

/* 
  This function gets the edit button from the customer page and redirects the user to the same page with a query parameter to show the form. 
*/
function editCustomerDetailsBUtton(){
  const editButton = document.querySelector('#edit_customer_details');
  if(editButton){
    editButton.addEventListener('click', ()=>{ 
        const host = window.location.origin; //get the host name. For example "https://localHost:8080"
        const newPathName = editButton.dataset.url; //get the url from the data- attribute on the element. 
        window.location.href = host + newPathName; //redirect the user. 
    });
  }
}

/* 
  This function gets the cancel button from the customer page and cancels the form submission by redirecting the user back to the same page with a query parameter removed, which toggles the form off. 
*/
function cancelCustomerEditForm(){
  const cancelButton = document.querySelector('#cancel');
  if(cancelButton){
    cancelButton.addEventListener('click', (e)=>{ 
      let url = window.location.href;
      if(url.includes('&edit=1')){
        url = url.replace('&edit=1', '');
      }
      window.location.href = url; //redirect the user. 
      e.preventDefault();
    });
  }
}